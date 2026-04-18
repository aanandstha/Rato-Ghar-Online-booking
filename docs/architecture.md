# 🏗️ System Architecture Document  
## Rato Ghar Online Ordering Platform  
### Week 4 Deliverable  

---

## 1. Introduction  

### 1.1 Purpose  
This document presents the technical architecture of the Rato Ghar Online Ordering Platform. It defines the system structure, components, technologies, and data flow required to support an online food ordering system.

### 1.2 Scope  
The system allows customers to browse menus, place food orders, and complete payments online, while administrators can manage menu items and orders efficiently.

---

## 2. Architecture Style Decision  

### 2.1 Monolith vs Microservices  

The system adopts a **Monolithic Architecture** for the initial implementation.

### 2.2 Justification  
- Simpler development and deployment  
- Suitable for small to medium-scale applications  
- Faster development for academic/prototype purposes  
- Easier debugging, testing, and maintenance  

### 2.3 Future Scalability  
As the system grows, it can transition into a **Microservices Architecture**, separating key services such as:
- Authentication Service  
- Order Service  
- Payment Service  

---

## 3. Technology Stack  

| Layer          | Technology                  |
|----------------|----------------------------|
| Frontend       | React.js, HTML, CSS        |
| Backend        | Node.js with Express       |
| Database       | MongoDB                    |
| Authentication | JWT (JSON Web Tokens)      |
| Hosting        | AWS / Local Server         |

---

## 4. High-Level Architecture  

The system follows a layered monolithic structure consisting of presentation, application, and data layers.

### 4.1 Components  

- **Frontend (Client Layer)**  
  Built using React.js to provide an interactive user interface.

- **Backend (Application Layer)**  
  Developed with Node.js and Express to handle business logic and API requests.

- **Database (Data Layer)**  
  MongoDB stores persistent data including users, orders, and menu items.

- **External Services**  
  Payment gateway integration for secure transaction processing.

---

## 5. System Architecture Diagram  

```mermaid
graph TD
    User --> Frontend
    Frontend --> Backend
    Backend --> Database
    Backend --> PaymentGateway
