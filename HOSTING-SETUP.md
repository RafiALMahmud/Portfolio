# Automatic Notification Cleanup Setup for Hosting

## What's Already Configured:
✅ CleanupOldNotifications command created
✅ Scheduled to run every hour in app/Console/Kernel.php
✅ Command deletes notifications older than 24 hours

## For Your Hosting Provider:

### 1. Shared Hosting (cPanel/WHM):
- Go to "Cron Jobs" in cPanel
- Add this cron job:
```
* * * * * cd /home/yourusername/public_html/lessence && php artisan schedule:run >> /dev/null 2>&1
```

### 2. VPS/Dedicated Server:
- SSH into your server
- Run: `crontab -e`
- Add this line:
```
* * * * * cd /var/www/html/lessence && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Cloud Hosting (DigitalOcean, AWS, etc.):
- Use their cron job interface
- Command: `php artisan schedule:run`
- Working directory: `/path/to/your/lessence`
- Schedule: Every minute (* * * * *)

### 4. Laravel Forge/Envoyer:
- Add scheduled job in dashboard
- Command: `php artisan schedule:run`
- Frequency: Every minute

## How It Works:
1. Cron runs Laravel scheduler every minute
2. Laravel checks if it's time to run our cleanup command
3. Every hour, notifications older than 24 hours are deleted
4. Database stays clean automatically

## Test Commands (run these after setup):
```bash
# Check if scheduler is working
php artisan schedule:list

# Run cleanup manually
php artisan notifications:cleanup

# Test the scheduler
php artisan schedule:run
```

## Important Notes:
- Replace `/path/to/your/lessence` with your actual project path
- Ensure PHP and Laravel are accessible from cron
- Some hosts require full paths to php executable
- Check hosting provider's documentation for cron setup
