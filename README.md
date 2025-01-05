# Inventory Management System

This project is a simple inventory management system built with PHP and MySQL. It allows users to manage products, perform CRUD operations, and register new users with different roles (admin and user).

## Features

- **User Registration**: Users can register with roles of either 'admin' or 'user'.
- **Product Management**: Admins can add, update, delete, and view products.
- **Search Functionality**: Users can search for products by name or price.
- **Responsive Design**: The interface is designed to be user-friendly on various devices.

## Technologies Used

- PHP
- MySQL
- Bootstrap (for styling)
- HTML/CSS/JavaScript

## Getting Started

To get a local copy up and running, follow these simple steps:

### Prerequisites

- A web server (like Apache or Nginx).
- PHP installed (version 7.4 or later recommended).
- MySQL database server.

### Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/yourusername/inventory-management-system.git
    ```

2. Navigate to the project directory:
    ```bash
    cd inventory-management-system
    ```

3. Import the database schema:
   - Create a new database in MySQL.
   - Run the SQL scripts located in the `database` folder (if applicable) to set up the required tables.

4. Update the database connection details in the PHP files if necessary.

5. Start your web server and navigate to the project in your browser.

## Usage

- **Register User**: Navigate to the user registration page and fill out the form to create a new account.
- **Admin Dashboard**: Admins can manage products from this section, including adding new products, updating existing ones, and deleting products.

## Contributing

Contributions are welcome! Please feel free to open an issue or submit a pull request.

1. Fork the repository.
2. Create your feature branch:
    ```bash
    git checkout -b feature/AmazingFeature
    ```
3. Commit your changes:
    ```bash
    git commit -m 'Add some AmazingFeature'
    ```
4. Push to the branch:
    ```bash
    git push origin feature/AmazingFeature
    ```
5. Open a pull request.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgements

- Inspired by various inventory management systems.
- Bootstrap for responsive design.
