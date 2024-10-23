# News Website

A dynamic web application that allows users to read the latest news articles across various categories. Built using PHP, HTML, CSS, and JavaScript, this platform provides a simple interface for browsing news articles, managing content, and more.

## Features

- **Latest News**: Get real-time updates of the latest news.
- **News Categories**: Browse news based on different categories (e.g., Technology, Sports, Health, etc.).
- **Search Functionality**: Easily search for articles using keywords.
- **Admin Panel**: Allows admin users to create, edit, and delete news articles.
- **Responsive Design**: Optimized for both desktop and mobile devices.

## Tech Stack

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL (for managing articles and user information)
- **Version Control**: Git

## Installation

Follow the steps below to set up the project on your local machine.

### Prerequisites

- XAMPP (or any local development environment with PHP & MySQL support)
- Git installed

### Steps

1. Clone the repository:

   ```bash
   git clone https://github.com/ayushmishra25/News_website.git

2. Move the project to your XAMPP htdocs directory:
   ```bash
   News_website /path/to/xampp/htdocs/

3. Start the XAMPP control panel and enable Apache and MySQL.

4. Import the database:

   Open phpMyAdmin from the XAMPP control panel.
   Create a new database (e.g., news_website).
   Import the SQL file located in the project folder (/db/news_website.sql).

5. Update the database connection in the project:
   Open config.php and modify the database connection details to match your local environment:
   ```php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "news_website";

6. Access the project by navigating to http://localhost/News_website in your browser.

## Usage
- **Users**: can browse news articles, search by keywords, and view the latest updates.
- **Admins**: can log in and manage news articles, including creating, updating, or deleting articles.

## Contributing
If you'd like to contribute to this project, please fork the repository and create a pull request. For major changes, please open an issue first to discuss what you'd like to change.

## License
This project is licensed under the MIT License. See the LICENSE file for more details.

## Contact
Author: Ayush Mishra
GitHub: ayushmishra25
