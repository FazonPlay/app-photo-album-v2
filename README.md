# 📸 Photo Album Management Application
Welcome to my (partially completed) project, **Photo Album Management Application!** 🖼️
This is a comprehensive web application where users can create, organize, and share photo albums online.
The application is built using **PHP, HTML, CSS, and JavaScript** following MVC architecture.

---

## 🚧 Current Issues
Although the core functionality is implemented, several issues remain:

1. **No images present for the front page** - There is some basic styling to make it look ok, however it needs a bit more work.

2. **Incomplete Logging System** - Logs work as intended but not everywhere, as the integration in those places wasn't seamless.

3. **Missing Search & Tags Functionality and Comments** - The search feature for finding photos by tags, dates, or album titles is not implemented, making photo discovery difficult. Comments weren't implemented either, however in the database they are present.     
                                             However, as an admin you can search PER user.

4. **CSS Styling Issues** - Small visual inconsistencies and styling problems throughout the interface.

5. **Not Mobile-First Design** - The application wasn't designed with mobile-first principles, leading to poor responsive behavior on smaller screens.

6. **Album Permission checks** - File size and format restrictions may not be properly enforced.

7. **Incomplete Documentation** - The README and inline documentation need to be expanded to cover all features and setup instructions.


---

## 🛠 Installation

To install the project, follow these steps:

### 1️⃣ Clone the Repository:
```
git clone https://github.com/FazonPlay/app-photo-album-v2
cd app-photo-album-v2
```

### 2️⃣ Install Dependencies:
Ensure **Composer** is installed, then navigate to the project directory and run:
```sh
composer require fakerphp/faker
```
This installs Faker, which generates fake user data.

You'll also need **Dotenv** for secure database connections:
```sh
composer require vlucas/phpdotenv
```

### 3️⃣ Setup Database Configuration:
Rename the `.env.dist` to `.env` file in the root directory with your database configuration:
```
DB_HOST="localhost"
DB_USER="your_username"
DB_PASSWORD="your_password"
DB_NAME="photo-album"
```

### 4️⃣ Create the Database:
Use **phpMyAdmin** or any database management tool to create a database with the name specified in your `.env` file.

### 5️⃣ Import the Database:
Import the `photo-album.sql` file from the `database` folder into your database.

### 6️⃣ Configure Web Server:
Ensure your web server (Apache/Nginx) is configured to serve PHP files and has proper permissions for the uploads directory.

### 7️⃣ Set Upload Permissions:
Make sure the uploads directory has write permissions:
```
chmod 755 uploads/
```

---

## 🎯 Usage

Once installed, navigate to the application URL to access the **homepage**, where you'll find:

✅ **Featured Public Albums**
✅ **Recent Activity**
✅ **Login/Register Options**

### 🔑 Authentication
- Click **Register** to create a new account
- Use **Login** to access your dashboard
- Complete your profile setup after registration

### 👤 User Features
- **Create & Manage Albums** - Add titles, descriptions, and tags
- **Upload Photos** - Add photos with descriptions
- **Share Albums** - Set privacy levels (private, public, group-restricted)
- **View Public Albums** - Browse albums by other users
- **Personal Dashboard** - View your albums, favorites, and invitations
- **Profile Management** - Update personal information and profile picture

### 🛠 Admin Features
- **User Management & CRUD** - Create, read, update, and delete user accounts
- **Album Management & CRUD** - Manage all albums, including private ones
- **Activity Logs** - Monitor user activities and system logs (partially)
- **Access Control** - Manage permissions for users and albums, as well as define roles
- **Everything a user can do** - Admins have full access to all features available to users

### 🔍 Planned Features (Not Yet Implemented)
- **Advanced Search** - Find photos by tags, dates, or album titles
- **Photo Filtering** - Sort and filter photos within albums
- **Social Sharing** - Share albums on social media platforms
- **Comments System** - Allow users to comment on photos and albums
- **Notifications** - Real-time notifications for album updates and comments
- **Mobile-First Design** - Redesign for better mobile responsiveness

---

## 📁 Project Structure
```
app-photo-album/
├── _partials/      # Reusable HTML components
├── assets/         # CSS, JavaScript, and static files
├── controller/     # MVC Controllers
├── database/       # SQL database
├── includes/       # Helper files, logger and db connection
├── logs/           # Application logs 
├── model/          # All SQL queries and database interactions
├── scripts/        # Was planning to make a script to generate dummy data.. Didn't make it in time
├── uploads/        # User uploaded photos
├── vendor/         # Composer dependencies
├── view/           # HTML templates and UI components
├── .env            # Environment configuration
├── .env.dist       # Environment template
├── .gitignore      # Git ignore rules
├── composer.json   # Composer configuration
├── composer.lock   # Composer lock file
├── index.php       # Main application entry point
└── README.md       # This file

```

---

## 🏗️ Technical Architecture

**Frontend:**
- Pure HTML5, CSS3, and JavaScript (ES6+)
- Object-Oriented JavaScript structure
- Responsive design with CSS Grid and Flexbox
- AJAX for dynamic content loading

**Backend:**
- PHP
- MVC (Model-View-Controller) architecture
- MySQL database
- Simple Authentication system

---

## 🔧 Known Technical Debt

1. **Code Documentation** - Needs comprehensive inline comments and PHPDoc blocks
2. **Performance** - Optimize database queries and implement caching
3. **Testing** - Add unit tests and integration tests
4. **Mobile UX** - Redesign with mobile-first approach

---

## 🤝 Contributors
- **[David]** - Full-stack development

---
