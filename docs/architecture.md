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
