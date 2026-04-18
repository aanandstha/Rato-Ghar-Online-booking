# System Architecture Document  
## Rato Ghar Online Ordering Platform  
### Week 4 Deliverable  

---

## 1. Introduction  

### 1.1 Purpose  
This document defines the technical architecture of the Rato Ghar Online Ordering Platform. It outlines the system structure, components, services, and data flow.

### 1.2 Scope  
The system allows customers to browse menus, place food orders, and complete payments online while enabling administrators to manage orders and menu items.

---

## 2. Architecture Style Decision  

### Monolith vs Microservices  

The system will follow a **Monolithic Architecture** for the initial version.

**Justification:**  
- Simpler to develop and deploy  
- Suitable for small to medium-scale applications  
- Faster development for prototype/academic project  
- Easier debugging and testing  

**Future Consideration:**  
The system can be migrated to microservices (e.g., separate services for authentication, orders, payments) as it scales.

---

## 3. Technology Stack  

| Layer          | Technology              |
|----------------|------------------------|
| Frontend       | React.js, HTML, CSS    |
| Backend        | Node.js with Express   |
| Database       | MongoDB                |
| Authentication | JWT (JSON Web Tokens)  |
| Hosting        | AWS / Local Server     |

---

 ## 4. High-Level Architecture  

             +-------------------------+
             |       User Browser      |
             +-----------+-------------+
                         |
                         v
             +-------------------------+
             |     Frontend (React)    |
             +-----------+-------------+
                         |
                         v
             +-------------------------+
             |     Backend (API)       |
             |   Node.js + Express     |
             +-----------+-------------+
                         |
         +---------------+---------------+
         |                               |
         v                               v
 +----------------+              +----------------------+
 |   Database     |              | External Services    |
 |   MongoDB      |              | Payment Gateway      |
 +----------------+              +----------------------+
---

## 5. System Components  

### 5.1 Frontend (Presentation Layer)  
- User interface for customers  
- Displays menu, cart, and orders  
- Sends HTTP requests to backend  

### 5.2 Backend (Application Layer)  
- Handles business logic  
- Manages user authentication  
- Processes orders and payments  
- Provides REST APIs  

### 5.3 Database (Data Layer)  
- Stores users, orders, menu items  
- Ensures persistent data storage  

---

## 6. Authentication Design  

Authentication will be implemented using **JWT (JSON Web Tokens)**.

### Flow:  
1. User logs in with email and password  
2. Backend verifies credentials  
3. JWT token is generated  
4. Token is sent to frontend  
5. Frontend stores token (localStorage/session)  
6. Token is sent with each request for authorization  

---

## 7. Data Flow  

1. User interacts with frontend (e.g., selects food)  
2. Frontend sends request to backend API  
3. Backend validates request and processes logic  
4. Backend communicates with database  
5. Database returns data  
6. Backend sends response to frontend  
7. Frontend updates UI  

---

## 8. API Design Overview  

| Method | Endpoint   | Description           |
|--------|------------|-----------------------|
| POST   | /register  | Create new user       |
| POST   | /login     | Authenticate user     |
| GET    | /menu      | Fetch menu items      |
| POST   | /order     | Place new order       |
| GET    | /orders    | View user orders      |

---

## 9. Security Considerations  

- Password hashing (bcrypt)  
- JWT-based authentication  
- HTTPS communication  
- Input validation and sanitization  

---

## 10. Deployment Architecture  


[ User Browser ]
|
v
[ Frontend (React App) ]
|
v
[ Backend Server (Node.js) ]
|
v
[ MongoDB Database ]


---

## 11. Scalability Considerations  

- Modular code structure  
- Cloud deployment ready  
- Can scale backend and database independently  
- Future migration to microservices  

---

## 12. Conclusion  

The chosen monolithic architecture provides a simple and efficient foundation for the platform while allowing future scalability and enhancements.

---
