## PHP Cache Class

This is a simple PHP caching class that allows you to save, retrieve, and delete cache data using files. 

### Features

- **Efficient Storage**: Data is stored in a compressed and base64-encoded format.
- **Metadata**: Each cache item contains metadata such as its creation and last updated time.
- **Flexible Directory Structure**: If the designated cache directory does not exist, the class will automatically create it.

### Prerequisites

Ensure that the directory where you want to store cache files has the appropriate write permissions. You should also set a constant or variable named `CACHE_LOCATION` with the directory path where cache files should be saved.

### How to Use

1. **Include the Cache Class**:

   ```php
   include 'CACHE.php';
   ```

2. **Initialize**:

   ```php
   $cache = new CACHE();
   ```

3. **Save Data to Cache**:

   ```php
   $result = $cache->SAVE('my_data_key', array('some' => 'data'));
   ```

4. **Retrieve Data from Cache**:

   ```php
   $data = $cache->GET('my_data_key');
   ```

5. **Delete Data from Cache**:

   ```php
   $result = $cache->DELETE('my_data_key');
   ```

### Responses

The class will return an associative array containing the status of the operation (`"OK"` or `"ERR"`) and a message describing the outcome. For successful `GET` operations, the data will also be included.

### Contributions

Feel free to contribute to this project by submitting pull requests or issues.
