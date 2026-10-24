"""Extract drawable SVG letter outlines from the site's licensed Playfair font.

Optional asset-generation helper: fontTools and Brotli are needed to regenerate.
Normal npm builds use the checked-in JSON and need no Python dependencies.
"""
import json
import re
import sys
from pathlib import Path

root = Path(__file__).resolve().parents[1]
sys.path.insert(0, str(root / "test-results/font-tools"))
from fontTools.ttLib import TTFont
from fontTools.varLib.instancer import instantiateVariableFont
from fontTools.pens.svgPathPen import SVGPathPen
from fontTools.pens.roundingPen import RoundingPen

source = (root / "src/content.ts").read_text(encoding="utf-8")
block = source.split("export const skills:", 1)[1].split("export const research", 1)[0]
characters = sorted(set("".join(re.findall(r'"([^\"]+)"', block))) - {" "})
font = TTFont(root / "public/fonts/playfair-latin.woff2")
if "fvar" in font:
    font = instantiateVariableFont(font, {"wght": 500}, inplace=False)
glyphs = font.getGlyphSet()
cmap = font.getBestCmap()
output = {
    "units": font["head"].unitsPerEm,
    "ascent": font["hhea"].ascent,
    "descent": font["hhea"].descent,
    "glyphs": {},
}
for character in characters:
    name = cmap[ord(character)]
    pen = SVGPathPen(glyphs)
    glyphs[name].draw(RoundingPen(pen, roundFunc=lambda value: round(value, 1)))
    output["glyphs"][character] = {
        "width": font["hmtx"].metrics[name][0],
        "path": pen.getCommands(),
    }
(root / "src/data/skillGlyphs.json").write_text(
    json.dumps(output, separators=(",", ":")), encoding="utf-8"
)
print(f"Generated {len(characters)} glyph outlines; no runtime font conversion required.")
