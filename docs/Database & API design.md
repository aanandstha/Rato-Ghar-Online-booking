# Week 5 — Database & API Design

## Expected Deliverable

Design and implement the database architecture and RESTful API structure for the Food Ordering Website system.

---

# Objective

The purpose of this phase was to establish a strong backend foundation for the application by designing a scalable database structure, defining API endpoints, and implementing validation rules to ensure secure and efficient data management.

---

# Activities Completed

- Designed relational database schema
- Identified system entities and relationships
- Defined REST API endpoints
- Planned request and response structures
- Implemented input validation rules
- Designed authentication workflow

---

# System Entities

The following entities were identified as core components of the Food Ordering Website:

| Entity | Description |
|---|---|
| Users | Stores customer and administrator information |
| Restaurants | Stores restaurant details |
| Food Items | Stores menu items available for ordering |
| Orders | Stores customer order information |
| Order Items | Stores individual items within an order |
| Payments | Stores payment transaction details |
| Reviews | Stores customer feedback and ratings |

---

# Database Schema Design

## 1. Users Table

| Field Name | Data Type | Constraints |
|---|---|---|
| user_id | INT | Primary Key |
| full_name | VARCHAR(100) | NOT NULL |
| email | VARCHAR(100) | UNIQUE |
| password | VARCHAR(255) | NOT NULL |
| phone | VARCHAR(20) | NOT NULL |
| role | ENUM('admin','customer') | DEFAULT 'customer' |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

---

## 2. Restaurants Table

| Field Name | Data Type | Constraints |
|---|---|---|
| restaurant_id | INT | Primary Key |
| restaurant_name | VARCHAR(100) | NOT NULL |
| location | VARCHAR(255) | NOT NULL |
| contact_number | VARCHAR(20) | NOT NULL |
| image_url | VARCHAR(255) | NULL |

---

## 3. Food_Items Table

| Field Name | Data Type | Constraints |
|---|---|---|
| food_id | INT | Primary Key |
| restaurant_id | INT | Foreign Key |
| food_name | VARCHAR(100) | NOT NULL |
| description | TEXT | NULL |
| category | VARCHAR(50) | NOT NULL |
| price | DECIMAL(10,2) | NOT NULL |
| image_url | VARCHAR(255) | NULL |
| availability | BOOLEAN | DEFAULT TRUE |

---

## 4. Orders Table

| Field Name | Data Type | Constraints |
|---|---|---|
| order_id | INT | Primary Key |
| user_id | INT | Foreign Key |
| total_amount | DECIMAL(10,2) | NOT NULL |
| order_status | ENUM('Pending','Preparing','Delivered','Cancelled') | DEFAULT 'Pending' |
| order_date | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

---

## 5. Order_Items Table

| Field Name | Data Type | Constraints |
|---|---|---|
| order_item_id | INT | Primary Key |
| order_id | INT | Foreign Key |
| food_id | INT | Foreign Key |
| quantity | INT | NOT NULL |
| subtotal | DECIMAL(10,2) | NOT NULL |

---

## 6. Payments Table

| Field Name | Data Type | Constraints |
|---|---|---|
| payment_id | INT | Primary Key |
| order_id | INT | Foreign Key |
| payment_method | VARCHAR(50) | NOT NULL |
| payment_status | ENUM('Paid','Pending','Failed') | DEFAULT 'Pending' |
| payment_date | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

---

## 7. Reviews Table

| Field Name | Data Type | Constraints |
|---|---|---|
| review_id | INT | Primary Key |
| user_id | INT | Foreign Key |
| food_id | INT | Foreign Key |
| rating | INT | CHECK (rating BETWEEN 1 AND 5) |
| comment | TEXT | NULL |

---

# Entity Relationship Design

The system relationships were designed to support efficient order processing and scalable database management.

| Relationship | Type |
|---|---|
| Restaurant → Food Items | One-to-Many |
| User → Orders | One-to-Many |
| Order → Order Items | One-to-Many |
| Food Item → Order Items | One-to-Many |
| Order → Payment | One-to-One |
| User → Reviews | One-to-Many |

---

# REST API Specification

## Authentication APIs

### Register User

```http
POST /api/auth/register
```

### Login User

```http
POST /api/auth/login
```

### Logout User

```http
POST /api/auth/logout
```

---

# Restaurant APIs

### Get All Restaurants

```http
GET /api/restaurants
```

### Get Restaurant Details

```http
GET /api/restaurants/:id
```

---

# Food APIs

### Get All Food Items

```http
GET /api/foods
```

### Get Food Item by ID

```http
GET /api/foods/:id
```

### Add Food Item

```http
POST /api/foods
```

### Update Food Item

```http
PUT /api/foods/:id
```

### Delete Food Item

```http
DELETE /api/foods/:id
```

---

# Order APIs

### Create Order

```http
POST /api/orders
```

### Get User Orders

```http
GET /api/orders/user/:id
```

### Update Order Status

```http
PUT /api/orders/:id
```

---

# Payment APIs

### Process Payment

```http
POST /api/payments
```

### View Payment History

```http
GET /api/payments/:id
```

---

# Validation Rules

The following validations were implemented to ensure data consistency and system security.

| Validation | Description |
|---|---|
| Email Validation | Ensures unique and valid email format |
| Password Validation | Minimum 6 characters with encryption |
| Price Validation | Price must be positive |
| Quantity Validation | Quantity must be greater than zero |
| Rating Validation | Ratings restricted between 1–5 |
| Required Fields | Prevents null input submission |

---

# Security Considerations

- JWT Authentication implemented
- Password hashing using bcrypt
- Input sanitization
- Protected admin routes
- Role-based authorization

---

# Technologies Used

| Technology | Purpose |
|---|---|
| Node.js | Backend runtime environment |
| Express.js | REST API framework |
| MongoDB / MySQL | Database management |
| JWT | User authentication |
| Postman | API testing |

---

