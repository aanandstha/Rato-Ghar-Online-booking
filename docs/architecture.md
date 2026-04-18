Technology Stack
Frontend: React.js
Backend: Node.js with Express
Database: PostgreSQL
Authentication: JSON Web Token (JWT)
Payment Integration: Stripe (Test Mode) 

Architecture Style
The system follows a Monolithic Architecture.
Simple to develop and manage for a small team
Faster implementation within project timeline
Suitable for prototype-level application

System Components
1. Frontend (Client Side)
User Interface built using React
Handles:
Menu display
Cart management
User interaction

Backend (Server Side)
REST API built with Node.js and Express
Handles:
Business logic
Authentication
Order processing
Payment handling

Database
PostgreSQL relational database
Stores:
Users
Menu items
Orders
Transactions

Authentication Design
Uses JWT (JSON Web Tokens)
Flow:
User logs in with credentials
Server validates user
JWT token is generated
Token is used for authenticated requests

Security Features:
Password hashing (e.g., bcrypt)
Token-based session management
Role-based access (Admin / Customer)

Data Flow
User sends request from frontend
Request goes to backend API
Backend processes request
Data is retrieved/stored in database
Response sent back to frontend

Payment Integration
Stripe used in test mode
Secure token-based payment processing
No sensitive card data stored in system

