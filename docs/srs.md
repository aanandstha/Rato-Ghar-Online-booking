# SRS
Week 3 Deliverable: Requirements Gathering - Rato Ghar Online Ordering

## 1. Project Overview
The Rato Ghar Online Ordering System is a full-stack web application designed to streamline the food ordering process for both customers and restaurant staff, replacing manual paper-based systems with a secure, digital solution.

---

## 2. User Personas
To ensure the system meets the needs of its primary stakeholders, we have defined the following personas:

### Persona A: The Busy Professional (Customer)
* **Name:** Rohan
* **Bio:** A 28-year-old software engineer who often works late and needs a quick, reliable way to order dinner.
* **Goal:** To browse the menu, customize an order (e.g., "extra spicy"), and pay securely within 2 minutes.
* **Pain Point:** Unclear delivery times and complicated payment forms.

### Persona B: The Kitchen Manager (Staff)
* **Name:** Srijana
* **Bio:** The head chef at Rato Ghar who manages high-volume orders during peak hours.
* **Goal:** To view a digital queue of incoming orders and update their status (Preparing -> Ready).
* **Pain Point:** Missing paper tickets and miscommunication with the front-of-house staff.

---

## 3. User Stories
These stories define the functional scope of the project:

| ID | As a... | I want to... | So that... |
|:---|:---|:---|:---|
| US.1 | Customer | Browse the menu by categories (Momos, Curry, Drinks) | I can find my desired food item quickly. |
| US.2 | Customer | Add items to a shopping cart and see the total price | I can manage my budget before checking out. |
| US.3 | Customer | Pay using a secure credit card interface (Stripe) | I don't have to carry cash or worry about data theft. |
| US.4 | Staff | Change an order status from "Pending" to "Cooking" | The customer is kept informed of their order progress. |
| US.5 | Admin | Update prices or mark items as "Out of Stock" | The digital menu always reflects current inventory. |

---

# Software Requirements Specification (SRS)

## 4. System Features (Functional Requirements)

### 4.1 User Account Management
* **FR-001 (User Registration):** The system shall allow new customers to create an account using their name, email, and password. *(High)*  
* **FR-002 (User Login):** The system shall authenticate users via email and password and issue a JWT session token upon successful login. *(High)*  
* **FR-003 (Password Security):** The system shall hash all passwords using **bcrypt** and never store plaintext passwords. *(High)*  
* **FR-004 (Profile Management):** Authenticated users shall be able to view and update their personal details. *(Medium)*  
* **FR-005 (Session Management):** The system shall invalidate JWT tokens on logout and enforce token expiry. *(High)*  
* **FR-006 (RBAC):** The system shall enforce role-based access control for Customer, Admin, and System Administrator roles. *(High)*  

### 4.2 Menu Management
* **FR-007 (Menu Display):** The system shall display all menu items with name, description, price, category, and image. *(High)*  
* **FR-008 (Category Filtering):** Users shall be able to filter items by category (e.g., Starters, Mains, Desserts, Drinks). *(Medium)*  
* **FR-009 (Item Search):** The system shall allow users to search for items by name or keyword. *(Medium)*  
* **FR-010 (Item Customisation):** Users shall be able to select add-ons and provide special instructions. *(Medium)*  
* **FR-011 (Admin Menu CRUD):** Administrators shall manage menu items and categories (create, read, update, delete). *(High)*  
* **FR-012 (Item Availability):** Administrators shall toggle item availability, hiding unavailable items. *(Medium)*  

### 4.3 Shopping Cart
* **FR-013 (Add to Cart):** Users shall be able to add menu items to a persistent cart. *(High)*  
* **FR-014 (Update Cart):** Users shall be able to modify quantities or remove items from the cart. *(High)*  
* **FR-015 (Cart Calculation):** The system shall dynamically calculate subtotal, taxes, and total price. *(High)*  
* **FR-016 (Cart Persistence):** The system shall retain cart data across sessions for logged-in users. *(Low)*  

### 4.4 Order Placement
* **FR-017 (Order Submission):** Users shall be able to place orders with delivery or pickup options. *(High)*  
* **FR-018 (Delivery Details):** The system shall collect and validate delivery address when applicable. *(High)*  
* **FR-019 (Order Instructions):** Users shall be able to add notes or special requests. *(Low)*  
* **FR-020 (Order Confirmation):** The system shall generate a unique order ID and show confirmation upon success. *(High)*  
* **FR-021 (Order History):** Users shall be able to view past orders and their statuses. *(Medium)*  

### 4.5 Payment Integration
* **FR-022 (Stripe Integration):** The system shall process payments using the Stripe API. *(High)*  
* **FR-023 (Payment Confirmation):** Orders shall be marked as “Paid” upon successful Stripe confirmation. *(High)*  
* **FR-024 (Payment Failure Handling):** The system shall handle failed payments with error messages and retry options. *(High)*  
* **FR-025 (Transaction Records):** The system shall store payment details including amount, status, and transaction ID. *(High)*  
* **FR-026 (Refund Support):** Administrators shall be able to initiate refunds (stretch goal). *(Low)*  

### 4.6 Order Management (Admin)
* **FR-027 (Order Dashboard):** Admins shall have access to a real-time order dashboard. *(High)*  
* **FR-028 (Status Updates):** Admins shall update order status (Pending → Completed). *(High)*  
* **FR-029 (Order Details):** Admins shall view full order information including items, customer, and payment status. *(High)*  
* **FR-030 (Order Cancellation):** Admins shall be able to cancel orders and notify customers. *(Medium)*  
* **FR-031 (Transaction History):** Admins shall access filterable transaction records. *(Medium)*  


---

## 5. Non-Functional Requirements (NFR)

### 5.1 Performance Requirements
* **NFR-001 (Performance – Page Load Time):** All customer-facing pages shall load within **3 seconds** under normal broadband conditions.  
* **NFR-002 (Performance – API Response):** Backend API endpoints shall return responses within **500 milliseconds** for standard operations.  
* **NFR-003 (Performance – Concurrent Users):** The system shall support a minimum of **50 concurrent users** without significant performance degradation.  
* **NFR-004 (Performance – Database Query Time):** Database queries shall complete within **200 milliseconds** for indexed lookups on datasets up to **10,000 records**.  

### 5.2 Safety Requirements
* **NFR-005 (Safety – Data Backup):** The database shall be backed up at least **once every 24 hours** during development and deployment.  
* **NFR-006 (Safety – Input Validation):** All user inputs shall be validated on both **client-side and server-side** to prevent data corruption.  
* **NFR-007 (Safety – Error Handling):** The system shall display **user-friendly error messages** while logging technical details server-side without exposing stack traces to users.  

### 5.3 Security Requirements
* **NFR-008 (Security – HTTPS Enforcement):** All data transmitted between client and server shall be encrypted using **HTTPS (TLS 1.2 or higher)**.  
* **NFR-009 (Security – Password Protection):** All passwords shall be hashed using **bcrypt** with a minimum cost factor of **10**.  
* **NFR-010 (Security – SQL Injection Prevention):** All database queries shall use **parameterised statements or ORM** to prevent SQL injection.  
* **NFR-011 (Security – JWT Handling):** JWT tokens shall include **expiry claims**, be invalidated on logout, and must not contain sensitive user data.  
* **NFR-012 (Security – CORS Policy):** The server shall enforce **CORS policies** allowing requests only from approved frontend origins.  
* **NFR-013 (Security – Payment Compliance):** Payment card data shall **never be stored** on platform servers; all handling shall be done via **Stripe’s PCI DSS-compliant infrastructure**.  
* **NFR-014 (Security – Rate Limiting):** Authentication endpoints shall implement **rate limiting** to mitigate brute-force attacks.  
---

## 6. Technology Stack (Sonika's Architecture Lead Role)
The system architecture is based on a 3-tier model:
* **Frontend:** React.js (Responsive UI)
* **Backend:** Node.js & Express (RESTful API)
* **Database:** PostgreSQL (Relational Data Storage)
* **Authentication:** JSON Web Tokens (JWT)
* **Payment:** Stripe API Integration
