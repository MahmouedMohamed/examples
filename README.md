# Modular Monolithic Laravel Kafka & MongoDB Integration

A Laravel application that is Modular Monolithic Architecture demonstrating the integration of Apache Kafka for event streaming and MongoDB for document storage. This project showcases a book management system with real-time event processing.

## 🚀 Features

- **MongoDB Integration**: Document-based storage using MongoDB
- **Apache Kafka**: Event streaming for real-time data processing
- **Event-Driven Architecture**: Automatic Kafka message publishing on model events
- **RESTful API**: CRUD operations for books with event triggers
- **Consumer Implementation**: Kafka consumers for processing events
- **Repository Pattern**: Clean separation of concerns with interfaces

## 📋 Prerequisites

- PHP 8.2+
- Composer
- Laravel 12.x
- MongoDB (local or cloud instance)
- Apache Kafka (local or cloud instance)
- Docker (optional, for local development)

## 🛠️ Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd <project-directory>
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Configuration

Copy the environment file and configure your settings:

```bash
cp .env.example .env
```

Update the `.env` file with your configuration:

```env
# Database Configuration
DB_URI=mongodb://localhost:27017

# Kafka Configuration
KAFKA_BROKERS=localhost:9092
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations (if applicable)

```bash
php artisan migrate
```

## 🏗️ Architecture Overview

### MongoDB Integration

The application uses MongoDB for document storage with the following structure:

- **Book Model**: Stores book information with MongoDB connection
- **Repository Pattern**: Clean data access layer
- **Service Layer**: Business logic implementation

### Kafka Event Streaming

The application implements event-driven architecture with Kafka:

- **Topics**: `book-created`, `book-updated`, `book-viewed`
- **Producers**: Automatic message publishing on model events
- **Consumers**: Message processing for each topic
- **Observers**: Model event listeners that trigger Kafka messages

## 🎯 SOLID Principles Implementation

This project follows **SOLID principles** to ensure clean, maintainable, and scalable code architecture.

### **1. Single Responsibility Principle (SRP)** ✅

Each class has a single, well-defined responsibility:

```php
// Book Model - Only handles data and MongoDB connection
class Book extends Model {
    protected $connection = 'mongodb';
    protected $fillable = ['title', 'publication_date', 'author'];
}

// BookController - Only handles HTTP requests and responses
class BookController {
    public function index(Request $request) { /* HTTP handling */ }
    public function store(StoreBookRequest $request) { /* HTTP handling */ }
}

// BookService - Only handles business logic
class BookService implements BookServiceInterface {
    public function index($request): LengthAwarePaginator { /* Business logic */ }
    public function store($request): Book { /* Business logic */ }
}

// BookRepository - Only handles data access
class BookRepository implements BookRepositoryInterface {
    public function index($request): LengthAwarePaginator { /* Data access */ }
    public function store($request): Book { /* Data access */ }
}

// KafkaService - Only handles Kafka operations
class KafkaService implements KafkaServiceInterface {
    public function createMessage($topic, $headers, $payload) { /* Kafka logic */ }
}
```

### **2. Open/Closed Principle (OCP)** ✅

The system is open for extension but closed for modification:

```php
// Interface allows new implementations without modifying existing code
interface BookServiceInterface {
    public function index($request): LengthAwarePaginator;
    public function store($request): Book;
}
```

### **3. Liskov Substitution Principle (LSP)** ✅

Subtypes are substitutable for their base types:

```php
// Any implementation can be substituted
$bookService = app(BookServiceInterface::class); // Works with any implementation

// Interface contracts are properly implemented
class BookService implements BookServiceInterface {
    public function index($request): LengthAwarePaginator { /* Implementation */ }
    public function store($request): Book { /* Implementation */ }
}
```

### **4. Interface Segregation Principle (ISP)** ✅

Clients are not forced to depend on interfaces they don't use:

```php
// Focused, specific interfaces
interface KafkaServiceInterface {
    public function initialize();
    public function createMessage($topic, $headers, $payload);
}

interface BookServiceInterface {
    public function index($request): LengthAwarePaginator;
    public function store($request): Book;
}

// No fat interfaces - each has specific, focused methods
```

### **5. Dependency Inversion Principle (DIP)** ✅

High-level modules depend on abstractions, not concretions:

```php
// Controllers depend on service interfaces
class BookController {
    public function __construct(private BookServiceInterface $bookService) {}
}

// Services depend on repository interfaces
class BookService {
    public function __construct(private BookRepositoryInterface $bookRepository) {}
}

// Proper dependency injection in ServiceProvider
class AppServiceProvider extends ServiceProvider {
    public function register(): void {
        $this->app->bind(BookServiceInterface::class, BookService::class);
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(KafkaServiceInterface::class, KafkaService::class);
        $this->app->bind(KafkaRepositoryInterface::class, KafkaRepository::class);
    }
}
```
### **🎯 Benefits of SOLID Implementation**

- **Maintainability**: Easy to modify and extend
- **Testability**: Components can be tested in isolation
- **Scalability**: New features can be added without breaking existing code
- **Reusability**: Components can be reused across the application
- **Flexibility**: Easy to swap implementations
- **Clean Architecture**: Clear separation of concerns

## 📁 Project Structure

```
app/
├── Models/
│   ├── Book.php              # MongoDB Book model
│   └── User.php              # User model
├── Observers/
│   └── BookObserver.php      # Model event observer
├── Interfaces/
│   ├── KafkaServiceInterface.php
│   ├── KafkaRepositoryInterface.php
│   ├── BookServiceInterface.php
│   └── BookRepositoryInterface.php
├── Services/
│   ├── KafkaService.php      # Kafka service implementation
│   └── BookService.php       # Book service implementation
├── Repositories/
│   ├── KafkaRepository.php   # Kafka repository
│   └── BookRepository.php    # Book repository
├── Kafka/
│   └── Consumers/
│       ├── BookCreatedConsumer.php
│       ├── BookUpdatedConsumer.php
│       └── BookViewedConsumer.php
└── Http/
    └── Controllers/
        ├── BookController.php # Book API controller
        └── KafkaController.php # Kafka initialization controller
```

## 🔧 Configuration

### MongoDB Configuration

The MongoDB connection is configured in `config/database.php`:

```php
'mongodb' => [
    'driver' => 'mongodb',
    'dsn' => env('DB_URI'),
    'database' => 'sample_db',
],
```

### Kafka Configuration

Kafka configuration is in `config/kafka.php` with the following topics:

```php
'topics' => [
    'book-created',
    'book-updated',
    'book-viewed',
],
```

## 🚀 Usage

### Starting the Application

```bash
# Start Laravel development server
php artisan serve

# Start Kafka consumers (in separate terminal)
php artisan kafka:consume-inline
```

### API Endpoints

#### Books API

```bash
# Get all books
GET /api/books

# Create a new book
POST /api/books
Content-Type: application/json

{
    "title": "Sample Book",
    "author": "Mahmoued Mohamed",
    "publication_date": "2024-01-01"
}

# Get a specific book
GET /api/books/{id}

# Update a book
PUT /api/books/{id}

# Delete a book
DELETE /api/books/{id}
```

#### Kafka Initialization

```bash
# Initialize Kafka topics
POST /api/kafka-initialize
```

### Event Flow

1. **Book Creation**: When a book is created via API
   - MongoDB stores the book document
   - `BookObserver::created()` is triggered
   - Kafka message is published to `book-created` topic
   - `BookCreatedConsumer` processes the message

2. **Book Update**: When a book is updated
   - MongoDB updates the book document
   - `BookObserver::updated()` is triggered
   - Kafka message is published to `book-updated` topic
   - `BookUpdatedConsumer` processes the message

3. **Book View**: When a book is viewed
   - `BookObserver::viewed()` is triggered
   - Kafka message is published to `book-viewed` topic
   - `BookViewedConsumer` processes the message

## 🧪 Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/BookTest.php

# Run tests with coverage
php artisan test --coverage
```

### Manual Testing

#### 1. Test MongoDB Connection

```bash
# Create a book via API
curl -X POST http://localhost:8000/api/books \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Book",
    "author": "Test Author",
    "publication_date": "2024-01-01"
  }'
```

#### 2. Test Kafka Integration

```bash
# Initialize Kafka topics
curl -X POST http://localhost:8000/api/kafka-initialize

# Create a book to trigger Kafka events
curl -X POST http://localhost:8000/api/books \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Kafka Test Book",
    "author": "Kafka Author",
    "publication_date": "2024-01-01"
  }'
```

#### 3. Monitor Kafka Messages

```bash
# Start Kafka consumer to see messages
php artisan kafka:consume-inline
```

### Unit Testing Examples

```php
// Test Book Model
public function test_book_can_be_created()
{
    $book = Book::create([
        'title' => 'Test Book',
        'author' => 'Test Author',
        'publication_date' => '2024-01-01'
    ]);

    $this->assertDatabaseHas('books', [
        'title' => 'Test Book'
    ]);
}

// Test Kafka Service
public function test_kafka_message_is_published()
{
    $kafkaService = app(KafkaServiceInterface::class);
    $result = $kafkaService->createMessage('test-topic', [], ['test' => 'data']);
    
    $this->assertNotNull($result);
}
```

## 🔍 Monitoring and Debugging

### Kafka Debug Mode

Enable Kafka debug mode in `.env`:

```env
KAFKA_DEBUG=true
```

### Logging

Kafka messages are logged when debug mode is enabled. Check Laravel logs:

```bash
tail -f storage/logs/laravel.log
```

### MongoDB Monitoring

Use MongoDB Compass or mongo shell to monitor data:

```bash
# Connect to MongoDB
mongo

# Switch to database
use sample_db

# Query books collection
db.books.find()
```

## 🐳 Docker Setup (Optional)

### Using Docker Compose

Create a `docker-compose.yml` file:

```yaml
version: '3.8'

services:
  app:
    build: .
    ports:
      - "8000:8000"
    volumes:
      - .:/var/www/html
    depends_on:
      - mongodb
      - kafka

  mongodb:
    image: mongo:latest
    ports:
      - "27017:27017"
    environment:
      MONGO_INITDB_DATABASE: sample_db

  zookeeper:
    image: confluentinc/cp-zookeeper:latest
    environment:
      ZOOKEEPER_CLIENT_PORT: 2181
      ZOOKEEPER_TICK_TIME: 2000

  kafka:
    image: confluentinc/cp-kafka:latest
    depends_on:
      - zookeeper
    ports:
      - "9092:9092"
    environment:
      KAFKA_BROKER_ID: 1
      KAFKA_ZOOKEEPER_CONNECT: zookeeper:2181
      KAFKA_ADVERTISED_LISTENERS: PLAINTEXT://localhost:9092
      KAFKA_OFFSETS_TOPIC_REPLICATION_FACTOR: 1
```

Run with Docker Compose:

```bash
docker-compose up -d
```

## 🚨 Troubleshooting

### Common Issues

1. **MongoDB Connection Error**
   - Verify MongoDB is running
   - Check connection string in `.env`
   - Ensure MongoDB extension is installed: `pecl install mongodb`

2. **Kafka Connection Error**
   - Verify Kafka broker is running
   - Check broker address in `.env`
   - Ensure topics are created

3. **Consumer Not Receiving Messages**
   - Check consumer group ID
   - Verify topic names match
   - Check offset reset policy

### Debug Commands

```bash
# Check MongoDB connection
php artisan tinker
>>> DB::connection('mongodb')->getMongoDB()->listCollections()

# Check Kafka topics
kafka-topics --list --bootstrap-server localhost:9092

# Check Kafka consumer groups
kafka-consumer-groups --bootstrap-server localhost:9092 --list
```

## 📚 Dependencies

### Main Dependencies

- **Laravel Framework**: 12.x
- **Laravel Kafka**: `mateusjunges/laravel-kafka` ^2.7
- **Laravel MongoDB**: `mongodb/laravel-mongodb` ^5.4

### Development Dependencies

- **PHPUnit**: For testing
- **Laravel Pail**: For log viewing
- **Laravel Sail**: For Docker development

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🔗 Useful Links

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Kafka Package](https://github.com/mateusjunges/laravel-kafka)
- [Laravel MongoDB Package](https://github.com/mongodb/laravel-mongodb)
- [Apache Kafka Documentation](https://kafka.apache.org/documentation/)
- [MongoDB Documentation](https://docs.mongodb.com/)
