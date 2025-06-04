# BoneScope

**BoneScope** is a full-stack web application developed as a **final year solo project** for the BSc (Hons) Software Engineering degree at the **University of Westminster (IIT Sri Lanka)**.

The system is designed to support fracture detection in X-ray images, combining modern web development with integrated machine learning components.

---

## 🔧 Technologies Used

- **Framework:** Laravel (Frontend & Backend)
- **Languages:** PHP, Python, JavaScript
- **Database:** MySQL
- **Other Tools:** Laravel Blade, REST APIs, Git, Postman
- **ML Tools:** TensorFlow / Keras (for object detection & classification models)

---

## 📌 Features

- Upload X-ray images via a user-friendly web interface
- Integrated APIs for predicting bone fractures
- Classification and object detection models to support diagnostic output
- Secure user and data handling via Laravel’s built-in authentication
- Responsive frontend with Laravel Blade templating

---

## 👨‍💻 My Role

This was a **solo project**:
- Designed and developed the **entire Laravel-based application**
- Trained and integrated **two machine learning models** (object detection and classification)
- Built **REST APIs** to connect the ML models with the Laravel system
- Focused on usability, model integration, and web performance

---

## 🚀 How to Run (Local Setup)

### Laravel Setup
```bash
git clone https://github.com/ganidu12/final-year-project.git
cd final-year-project
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
