/**
 * Google Apps Script Webhook for L'essence Order Management
 * 
 * Instructions:
 * 1. Go to https://script.google.com
 * 2. Create a new project
 * 3. Replace the default code with this script
 * 4. Deploy as a web app with execute permissions for "Anyone"
 * 5. Copy the web app URL and add it to your .env file as GOOGLE_SHEETS_WEBHOOK
 * 6. Make sure your Google Sheet has the correct headers in row 1:
 *    Name | order | User_id | Phone number | Address | Quantity | status | total amount | Order Number
 */

// Configuration
const SHEET_ID = '12VmuVQtpZ-wsfy8oMRh_78OyVe_FscSA463ofg16weI'; // Replace with your actual sheet ID
const SHEET_NAME = 'Sheet1'; // Replace with your actual sheet name

/**
 * Main function to handle POST requests from Laravel
 */
function doPost(e) {
  try {
    // Parse the incoming data
    const data = JSON.parse(e.postData.contents);
    
    // Get the spreadsheet
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(SHEET_NAME);
    
    // Add the order data to the sheet
    const rowData = [
      data.Name || '',
      data.order || '',
      data.User_id || '',
      data['Phone number'] || '',
      data.Address || '',
      data.Quantity || 0,
      data.status || 'pending',
      data['total amount'] || '0.00',
      data['Order Number'] || ''
    ];
    
    // Append the row to the sheet
    sheet.appendRow(rowData);
    
    // Return success response
    return ContentService
      .createTextOutput(JSON.stringify({success: true, message: 'Order added successfully'}))
      .setMimeType(ContentService.MimeType.JSON);
      
  } catch (error) {
    // Log error and return error response
    console.error('Error processing order:', error);
    return ContentService
      .createTextOutput(JSON.stringify({success: false, error: error.toString()}))
      .setMimeType(ContentService.MimeType.JSON);
  }
}

/**
 * Function to handle GET requests (for testing)
 */
function doGet(e) {
  return ContentService
    .createTextOutput('L\'essence Order Webhook is running!')
    .setMimeType(ContentService.MimeType.TEXT);
}

/**
 * Function to update order status
 * This can be called separately or integrated with the main webhook
 */
function updateOrderStatus(orderNumber, newStatus) {
  try {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(SHEET_NAME);
    const data = sheet.getDataRange().getValues();
    
    // Find the row with the matching order number
    for (let i = 1; i < data.length; i++) { // Start from row 2 (skip header)
      if (data[i][8] === orderNumber) { // Order Number is in column I (index 8)
        // Update the status (column G, index 6)
        sheet.getRange(i + 1, 7).setValue(newStatus);
        return true;
      }
    }
    
    return false; // Order not found
  } catch (error) {
    console.error('Error updating order status:', error);
    return false;
  }
}

/**
 * Function to get all orders (for admin dashboard)
 */
function getAllOrders() {
  try {
    const sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(SHEET_NAME);
    const data = sheet.getDataRange().getValues();
    
    // Convert to array of objects
    const orders = [];
    const headers = data[0];
    
    for (let i = 1; i < data.length; i++) {
      const order = {};
      headers.forEach((header, index) => {
        order[header] = data[i][index];
      });
      orders.push(order);
    }
    
    return orders;
  } catch (error) {
    console.error('Error getting orders:', error);
    return [];
  }
}

/**
 * Function to get orders by status
 */
function getOrdersByStatus(status) {
  const allOrders = getAllOrders();
  return allOrders.filter(order => order.status === status);
}

/**
 * Function to get order statistics
 */
function getOrderStats() {
  const allOrders = getAllOrders();
  
  const stats = {
    total: allOrders.length,
    pending: allOrders.filter(o => o.status === 'pending').length,
    delivered: allOrders.filter(o => o.status === 'delivered').length,
    couldNotBeDelivered: allOrders.filter(o => o.status === 'could not be delivered').length,
    totalRevenue: allOrders.reduce((sum, order) => sum + parseFloat(order['total amount'] || 0), 0)
  };
  
  return stats;
}




