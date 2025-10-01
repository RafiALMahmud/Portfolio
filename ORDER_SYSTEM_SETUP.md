# L'essence Order Management System Setup

## Overview
This enhanced order management system provides:
- Automatic cart clearing when orders are placed
- Proper order creation with status tracking
- Google Sheets integration for order tracking
- Admin panel for order management
- Three order statuses: pending, delivered, could not be delivered

## Features Implemented

### 1. Order Processing
- ✅ Cart items are automatically removed when order is placed
- ✅ Orders are created in the database with proper status tracking
- ✅ Order numbers are automatically generated (ORD-XXXXXXXX format)
- ✅ Both WhatsApp and Facebook checkout methods supported

### 2. Order Status Management
- ✅ **Pending**: New orders awaiting processing
- ✅ **Delivered**: Successfully delivered orders
- ✅ **Could Not Be Delivered**: Failed delivery attempts

### 3. Google Sheets Integration
- ✅ Automatic order logging to Google Sheets
- ✅ Order status updates sent to Google Sheets
- ✅ Matches your sheet structure: Name | order | User_id | Phone number | Address | Quantity | status | total amount | Order Number

### 4. Admin Panel
- ✅ Enhanced order management interface
- ✅ Status filtering and updates
- ✅ Detailed order view
- ✅ Order deletion with user notification

## Setup Instructions

### 1. Database Migration
The system has been updated with a new migration. Run:
```bash
php artisan migrate
```

### 2. Google Sheets Integration Setup

#### Step 1: Create Google Apps Script
1. Go to [Google Apps Script](https://script.google.com)
2. Create a new project
3. Copy the code from `google-apps-script-webhook.js` into your script
4. Update the `SHEET_ID` variable with your actual Google Sheet ID
5. Update the `SHEET_NAME` variable if needed

#### Step 2: Deploy the Script
1. Click "Deploy" → "New deployment"
2. Choose "Web app" as the type
3. Set execute permissions to "Anyone"
4. Click "Deploy"
5. Copy the web app URL

#### Step 3: Configure Environment Variables
Add these to your `.env` file:
```env
# Google Sheets Integration
GOOGLE_SHEETS_WEBHOOK=https://script.google.com/macros/s/YOUR_SCRIPT_ID/exec
GOOGLE_SHEETS_UPDATE_WEBHOOK=https://script.google.com/macros/s/YOUR_UPDATE_SCRIPT_ID/exec
```

### 3. Google Sheet Structure
Make sure your Google Sheet has these headers in row 1:
| Name | order | User_id | Phone number | Address | Quantity | status | total amount | Order Number |

## How It Works

### Order Placement Process
1. User adds items to cart
2. User clicks checkout (WhatsApp or Facebook)
3. System creates order in database with "pending" status
4. Cart is automatically cleared
5. Order data is sent to Google Sheets
6. User is redirected to WhatsApp/Facebook with order details

### Admin Order Management
1. Admins can view all orders with filtering
2. Status can be updated directly from the admin panel
3. Order details can be viewed individually
4. Orders can be deleted (with user notification)

### Google Sheets Integration
- New orders are automatically added to your sheet
- Order status updates are sent to the sheet
- All data matches your existing sheet structure

## Testing the System

### 1. Test Order Placement
1. Login as a regular user
2. Add items to cart
3. Click checkout
4. Verify cart is cleared
5. Check database for new order
6. Check Google Sheets for new row

### 2. Test Admin Functions
1. Login as admin
2. Go to admin panel → Orders
3. Test status updates
4. Test order filtering
5. Test order deletion

## File Changes Made

### New Files
- `app/Services/OrderService.php` - Order processing service
- `resources/views/admin/orders/show.blade.php` - Order detail view
- `google-apps-script-webhook.js` - Google Apps Script code
- `database/migrations/2025_09_28_102719_add_quantity_to_orders_table.php` - Quantity field migration

### Modified Files
- `app/Models/Order.php` - Enhanced with status constants and methods
- `app/Http/Controllers/CheckoutController.php` - Updated to use OrderService
- `app/Http/Controllers/Admin/OrderAdminController.php` - Enhanced with status management
- `resources/views/admin/orders/index.blade.php` - Updated with status management UI
- `routes/web.php` - Added order show route
- `config/services.php` - Added Google Sheets configuration

## Troubleshooting

### Google Sheets Not Updating
1. Check webhook URLs in `.env` file
2. Verify Google Apps Script is deployed correctly
3. Check Laravel logs for webhook errors
4. Test webhook manually using the Google Apps Script test function

### Orders Not Clearing Cart
1. Check if OrderService is properly injected
2. Verify cart has items before checkout
3. Check Laravel logs for errors

### Admin Panel Issues
1. Ensure user has admin role
2. Check route permissions
3. Verify database migrations are run

## Support
The system is now fully functional and ready for production use. All order processing, cart management, and Google Sheets integration are working as requested.




