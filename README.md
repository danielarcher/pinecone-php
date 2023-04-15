# PHP Pinecode API Client

This PHP Pinecode API Client is a powerful and easy-to-use library that allows you to interact with the Pinecode vector database. Pinecode is a scalable and high-performance vector database designed to store, search, and manage large volumes of high-dimensional vectors. 

This library offers a convenient and efficient way to interact with Pinecode using PHP.

## Features
- Easy-to-use interface for interacting with Pinecode API
- Perform CRUD operations on vectors and collections
- Supports batch operations for improved efficiency
- Pagination and filtering support for search queries
- Well-documented and structured code
- Comprehensive error handling and logging

## Installation

To install the PHP Pinecode API Client, simply run the following command:

```shell
composer require danielarcher/pinecode-php
```

This will add the library to your project's dependencies and make it available for use.

## Getting Started
To get started, you will need to create a new instance of the PinecodeClient class and provide your Pinecode API key:

```php
require_once 'vendor/autoload.php';

use Danielarcher\PinecodeApiClient\PinecodeClient;

$apiKey = 'your-api-key';
$pinecodeClient = new PinecodeClient($apiKey);
```
Now you can start interacting with the Pinecode API using the methods provided by the PinecodeClient class.

# Examples
Create a collection
```php
$collectionName = 'example_collection';
$dimensions = 128;

$response = $pinecodeClient->createCollection($collectionName, $dimensions);
```
Insert a vector
```php
$vectorId = 'example_vector';
$vector = [0.1, 0.2, 0.3, ...];

$response = $pinecodeClient->insertVector($collectionName, $vectorId, $vector);
```
Search for similar vectors
```php
$queryVector = [0.1, 0.2, 0.3, ...];
$topK = 10;

$response = $pinecodeClient->search($collectionName, $queryVector, $topK);
```
Update a vector
```php
$newVector = [0.4, 0.5, 0.6, ...];

$response = $pinecodeClient->updateVector($collectionName, $vectorId, $newVector);
```
Delete a vector
```php
$response = $pinecodeClient->deleteVector($collectionName, $vectorId);
Delete a collection
```php
$response = $pinecodeClient->deleteCollection($collectionName);
```
## Documentation
For a more detailed explanation of the available methods and their usage, please refer to the [official documentation](https://github.com/yourusername/php-pinecode-api-client/wiki).

## Support
If you encounter any issues or require further assistance, please submit an issue on our GitHub repository.

## Contributing
We welcome contributions from the community. If you would like to contribute, please fork the repository, make your changes, and submit a pull request.

## License
This project is licensed under the MIT License - see the LICENSE file for details.