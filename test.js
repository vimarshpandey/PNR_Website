const fs = require('fs');
const apiEndpoint = 'https://travel.paytm.com/api/trains/v1/status?vertical=train&client=web&is_genuine_pnr_web_request=1&pnr_number=2409094894';
//210756830
//2409094894
const jsonFilePath = 'fetchedData.json';

fetch(apiEndpoint)
  .then(response => {
    if (!response.ok) {
      throw new Error(`Failed to fetch data. Status: ${response.status}`);
    }
    return response.json();
  })
  .then(newData => {
    if (newData.error) {
      throw new Error(`Error fetching data: ${newData.error}`);
    }

    // Read the existing data from the file, or create an empty array if the file doesn't exist
    let existingData = [];
    try {
      const dataFromFile = fs.readFileSync(jsonFilePath, 'utf8');
      existingData = JSON.parse(dataFromFile);
    } catch (error) {
      // File might not exist or there could be a parsing error, which is okay
    }

    // Erase the previous data and add the new fetched data
    existingData = [newData];

    // Write the updated data back to the file
    fs.writeFileSync(jsonFilePath, JSON.stringify(existingData, null, 2));

    console.log('Fetched data:', newData);
  })
  .catch(error => {
    console.error('Error:', error.message);
  });