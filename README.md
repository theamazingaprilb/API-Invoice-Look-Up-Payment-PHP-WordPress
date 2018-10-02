# API-Invoice-Look-Up-Payment-PHP-WordPress
// Author: Doug Marshall
// Author Site: http://www.theamazingdoug.com
// Author Github: https://www.github.com/justplaindoug

A series of functions developed for a WordPress site that needed to make API calls in order to look up individual invoices and process payments, as well as looking up all invoices for a date range and converting them to an IIF file for import into Quickbooks.

Because all of this was going to be locked down by IP on the server-level, it needed to be done through PHP and then parsing out the JSON to javascript so that all math could be done and payment processed through stripe.

The first file contains the functions to process a call to the API to look up an invoice, pass the balance to a stripe payment interface, and then send payment information back through the API with payment amount and floating balance.

The second file contains the search fields for a date range, allowing for invoice lookup. Once that is completed, the user need only click one button to create the file that is uploaded to Quickbooks.
