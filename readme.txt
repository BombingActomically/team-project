# 🎉 Evenza – Multi College Event Registration Portal

## 📌 Project Overview

**Evenza** is a full-stack web application designed to manage and streamline event registration across multiple colleges. It provides a centralized platform where students can explore events, register, and generate entry passes, while organizers and admins can efficiently manage events and users.

---

## 🚀 Features

### 👨‍🎓 Student

* Browse available events
* Register for events
* View registered events
* Download/View entry passes

### 🧑‍💼 Organizer

* Create, edit, and delete events
* Manage participants
* Verify entry passes

### 🛠️ Admin

* Manage users (students & organizers)
* Manage colleges
* Full system control

---

## 🏗️ Tech Stack

* **Frontend:** HTML, CSS, Bootstrap, JavaScript
* **Backend:** Core PHP (No Framework)
* **Database:** MySQL
* **Authentication:** Session-based login system

---

## 📁 Project Structure

```
evenza/
│
├── config/            # Database & configuration files
├── includes/          # Reusable components (header, footer, functions)
├── auth/              # Login, Register, Logout
├── admin/             # Admin dashboard & management
├── organizer/         # Organizer panel
├── student/           # Student panel
├── public/            # Public pages (home, events)
├── assets/            # CSS, JS, Images
└── .htaccess          # Security & routing (optional)
```

---

## 🗄️ Database Design

The system uses a relational database with the following main tables:

* **colleges** – Stores college information
* **users** – Stores user data with roles (student, organizer, admin)
* **events** – Event details
* **event_registrations** – Student registrations
* **entry_passes** – Unique event passes
* **categories** – Event categories
* **event_categories** – Mapping events to categories

✔ Optimized using indexing for better performance
✔ Uses foreign keys for data integrity

---

## 🔐 User Roles & Access Control

| Role      | Access Level        |
| --------- | ------------------- |
| Admin     | Full system control |
| Organizer | Event management    |
| Student   | Event participation |

---

## ⚙️ Installation & Setup

1. Clone the repository:

   ```bash
   git clone https://github.com/your-username/evenza.git
   ```

2. Move project to your server directory:

   * XAMPP → `htdocs/`
   * WAMP → `www/`

3. Import the database:

   * Open **phpMyAdmin**
   * Create a database (e.g., `evenza_db`)
   * Import the SQL file

4. Configure database connection:

   * Go to `config/db.php`
   * Update:

     ```php
     $conn = mysqli_connect("localhost", "root", "", "evenza_db");
     ```

5. Run the project:

   ```
   http://localhost/evenza
   ```

---

## 📸 Screenshots (Optional)

*Add screenshots of your UI here*

---

## 🌟 Key Highlights

* Clean and scalable folder structure
* Role-based authentication system
* Multi-college support
* Secure and optimized database
* Beginner-friendly architecture

---

## 📌 Future Enhancements

* Email notifications
* QR code-based entry passes
* Payment integration
* REST API support
* Admin analytics dashboard

---

## 👨‍💻 Author

**Darshan Patel**
Full-Stack Developer (PHP & MERN)

---

## 📄 License

This project is open-source and available for learning and educational purposes.
