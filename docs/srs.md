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

## 4. System Requirements

### 4.1 Functional Requirements (FR)
* **FR-1:** The system shall allow users to register and log in securely.
* **FR-2:** The system shall display real-time updates of the menu from the PostgreSQL database.
* **FR-3:** The system shall process payments and generate a unique Order ID.
* **FR-4:** The system shall provide an administrative dashboard for order management.

### 4.2 Non-Functional Requirements (NFR)
* **NFR-1 (Security):** All user passwords must be hashed using the **Bcrypt** algorithm.
* **NFR-2 (Privacy):** No raw credit card data shall be stored; all payments must use **Stripe Tokenization**.
* **NFR-3 (Performance):** The menu page should load in under 2.5 seconds on a standard 4G connection.
* **NFR-4 (Reliability):** The system must maintain 99.5% uptime during business hours.

---

## 5. Technology Stack (Sonika's Architecture Lead Role)
The system architecture is based on a 3-tier model:
* **Frontend:** React.js (Responsive UI)
* **Backend:** Node.js & Express (RESTful API)
* **Database:** PostgreSQL (Relational Data Storage)
* **Authentication:** JSON Web Tokens (JWT)
* **Payment:** Stripe API Integration
