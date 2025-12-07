# Primal Tutoring Services

A web-based tutoring system built with PHP and MySQL, featuring user management, scheduling, bookings, and reviews.

## Features

- User roles: Admin, Tutor, Student
- Subject management
- Tutor scheduling
- Booking system
- Review system
- Responsive design with Tailwind CSS

## Setup Instructions

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache or Nginx web server
- XAMPP or similar for local development

### Installation

1. **Clone or Download the Project**
   - Place the project files in your web server's root directory (e.g., `htdocs` for XAMPP).

2. **Database Setup**
   - Create a new MySQL database named `tutoring_system`.
   - Import the `db.sql` file to create the necessary tables:
     ```sql
     mysql -u your_username -p tutoring_system < db.sql
     ```
     Or use phpMyAdmin to import the file.

3. **Configuration**
   - Open `config.php` and update the database connection details:
     ```php
     $servername = "localhost";
     $username = "your_db_username";
     $password = "your_db_password";
     $dbname = "tutoring_system";
     ```

4. **Web Server Configuration**
   - Ensure your web server is configured to serve PHP files.
   - For XAMPP, start Apache and MySQL services.

5. **Access the Application**
   - Open your browser and navigate to `http://localhost/cit17-finals/home-index.php` - this is the homepage of our project
   - There is a Button for Register as a new user or login with existing credentials.
### Default Admin Account
FOR ADMIN ACCOUNT
- Email: admin@gmail.com (static account)
- Password: admin123 (static/hardcoded account)
*After you logged in, you can add admin account on user management also.

## Usage

### For Students
- Register/Login
- View available schedules
- Book tutoring sessions
- Leave reviews after completed sessions

### For Tutors
- Login (assigned by admin)
- Manage schedules
- View bookings

### For Admins
- Manage users, subjects, schedules, bookings, reviews
- Change password

## File Structure

- `home-index.php` - Homepage 
- `login.php` - Login page
- `register.php` - Registration page
- `admin.php` - Admin dashboard
- `index-student.php` - Student dashboard
- `teacher.php` - Teacher/Tutor dashboard
- `config.php` - Database configuration
- `db.sql` - Database schema
- Various PHP files for CRUD operations

## Technologies Used

- PHP
- MySQL
- Tailwind CSS
- HTML5

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open source and available under the [MIT License](LICENSE).
