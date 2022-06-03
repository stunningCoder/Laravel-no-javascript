
# Requirements
Everything required for Marketplace to run normally

## PHP Extensions
-  sodium   (Message encryptuon)
-  gmp (Precision calculation)
   ```
    apt-get install php7.4-gmp
    ```
-  xmlrpc (Bitmessage communication protocol)
   ```
   sudo apt-get install php7.4-xmlrpc
   ```

## Additional services
- Bitcoind (Processing transactions)
- Elasticsearch (Searching trough records)
