# PHP Pinecone API Client

This PHP Pinecone API Client is a powerful and easy-to-use library that allows you to interact with the Pinecone vector database. Pinecone is a scalable and high-performance vector database designed to store, search, and manage large volumes of high-dimensional vectors. 

This library offers a convenient and efficient way to interact with Pinecone using PHP.

## Getting Started
### Prerequisites
To use this package, you will need to have PHP 8.1 or higher installed on your machine. You will also need to have a Pinecone account and an API key.

### Installation
To install the package, you can use composer:

```bash
composer require darcher/pinecone-php
```
# Usage

## Index
To get started, you will need to create an instance of the IndexApi class:

```php
use Darcher\PineconePhp\IndexApi;

$apiKey = 'your-api-key';
$environment = 'us-west1-gcp';

$pinecone = IndexApi::build($apiKey, $environment);
```
Once you have an instance of the IndexApi class, you can use it to perform various operations on your Pinecone indexes. For example, you can list all of your indexes:

```php
$indexes = $pinecone->list();
```
You can also create a new index:

```php
$name = 'my-new-index';
$dimension = 256;

$response = $pinecone->create($name, $dimension);
```
For more information on the available methods and their parameters, please refer to the inline documentation in the IndexApi class.

Usage
To get started, you will need to create an instance of the VectorApi class:

## Vectors

If you don't know the host

```php
use Darcher\PineconePhp\VectorApi;

$apiKey = 'your-api-key';
$environment = 'us-west1-gcp';
$indexName = 'your-index-name';

$pinecone = VectorApi::build($apiKey, null, $indexName, $environment);
```
Once you have an instance of the VectorApi class, you can use it to perform various operations on your Pinecone vectors. For example, you can query vectors by their ID:

```php
$id = 'your-vector-id';
$topK = 10;

$response = $pinecone->queryById($id, $topK);
```
You can also upsert a collection of vectors:

```php
use Darcher\PineconePhp\Vector;
use Darcher\PineconePhp\VectorCollection;

$vectors = new VectorCollection([
    new Vector('vector-id-1', [1, 2, 3]),
    new Vector('vector-id-2', [4, 5, 6]),
]);

$response = $pinecone->upsertCollection($vectors);
```
Or using the factory class
```php

use Darcher\PineconePhp\VectorCollectionFactory;

$response = $pinecone->upsertCollection(VectorCollectionFactory::create([
    ['vector-id-1', [1, 2, 3]],
    ['vector-id-2', [3, 4, 5]],
]));
```
You can also use the upsert for a single vector
```php
use Darcher\PineconePhp\Vector;

$response = $pinecone->upsertOne(new Vector('vector-id-1', [1, 2, 3]));
```



For more information on the available methods and their parameters, please refer to the inline documentation in the VectorApi class.

# Contributing

If you would like to contribute to this package, please feel free to open a pull request or an issue on the GitHub repository.

# License
This package is licensed under the MIT License.
