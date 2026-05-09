# 🏛️ AndRPaid
### Accredited Network for Distributed Research Partnerships And Institutional Development

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![KaTeX](https://img.shields.io/badge/KaTeX-Math-green?style=for-the-badge)

**AndRPaid** is a centralized research management platform designed to bridge the gap between Lecturers and Universities. 

Unlike fragmented tools (Google Docs for writing, Email for networking, Excel for data), AndRPaid provides a unified ecosystem for managing the entire research lifecycle—from idea generation and team formation to methodology design and final publication—while validating academic identities through a robust university affiliation system.

---

## 📑 Table of Contents
- [Problem Statement](#-problem-statement)
- [Key Features](#-core-features)
- [Technical Architecture](#-technical-architecture)
- [Database Schema](#-database-schema)
- [User Flows](#-user-flows)
- [Installation & Setup](#-installation--setup)
- [Roadmap](#-future-roadmap)
- [Contributing](#-contribution)

---

## 🚩 Problem Statement
The current academic research landscape is fragmented and inefficient:

1.  **Disconnected Workflows:** Researchers switch between dozens of apps (Overleaf, Trello, Excel, Email) to manage a single paper.
2.  **Verification Trust Issues:** It is difficult to verify if a potential collaborator is actually an active faculty member at a claimed university.
3.  **Collaboration Friction:** Finding researchers with specific interests (e.g., "Computer Vision in Jakarta") is manual and slow.
4.  **University Oversight:** Institutions lack a real-time dashboard to track their faculty's ongoing research progress before publication.

---

## 🌟 Core Features

### 🔐 A. Authentication & Roles
* **Dual-Role System:** Distinct workflows for **Lecturers** (Researchers) and **Universities** (Admins/Grantors).
* **Profile Identification:** UUID-based profiles (e.g., `/019ba6d9-d9fc.../overview`) ensuring secure and unique portfolio links.

### 📊 B. The Dashboard
* **Lecturer View:** Tracks active papers, pending collaboration invites, and total star/citation metrics.
* **University View:** Monitors affiliated lecturers, tracks total institutional research output, and manages incoming affiliation requests.
* **Smart Recommendations:** Suggests collaborators based on research fields and university networks.

### 📝 C. The Research Workspace (The Core)
A dedicated environment for every paper containing four key modules:
1.  **Literature Review Matrix:** Dynamic synthesis table (Author, Year, Method, Result), Theme/Tag management, and BibTeX export.
2.  **Methodology Studio:**
    * **Dataset Manager:** Upload samples and describe data sources.
    * **Formula Editor:** LaTeX integration (**KaTeX**) for rendering mathematical models.
    * **Code Repository:** Embed executable notebooks or GitHub Gists.
3.  **Results & Analysis:** Interactive chart and table builders with a drafting section for interpretation.
4.  **Conclusion & Future Works:** Structured drafting for summary, limitations, and future directions with a "Finalize" locking mechanism.

### 🌐 D. Networking & Discovery
* **Researcher Directory:** A searchable database with filters for Name, University, Province, and Research Interests.
* **Affiliation System:** Lecturers must request affiliation with a University. Universities act as "Gatekeepers," granting verification badges.

---

## 🏗 Technical Architecture

### Tech Stack
| Component | Technology | Description |
| :--- | :--- | :--- |
| **Backend** | PHP (Laravel 11) | MVC Architecture, Eloquent ORM, Authentication |
| **Frontend** | Blade Templates | Server-side rendering with Bootstrap 5 for UI |
| **Database** | MySQL | Relational data integrity (Foreign Keys, JSON columns) |
| **Math Engine** | KaTeX | Fast, client-side LaTeX rendering |
| **Icons** | Bootstrap Icons | Visual cues for UI elements |

---

## 🗄 Database Schema
Key models utilized in the application:

* **Users:** Base login info (`email`, `password`, `profileId`).
* **Lecturers:** Academic info (`user_id`, `province_id`, `bio`).
* **Universities:** Institutional info (`user_id`, `location`, `website`).
* **Papers:** The central entity (`title`, `status`, JSON fields for methodology/formulas).
* **Collaborations:** Pivot table linking papers to lecturers with roles (e.g., "Editor", "Viewer").
* **Affiliations:** Links lecturers to universities with status (`pending`, `verified`, `rejected`).

---

## 🔄 User Flows

### Flow 1: The "New Project" Lifecycle
1.  **Creation:** Lecturer clicks "New Project" on Dashboard → Enters Title & Visibility.
2.  **Team Building:** Lecturer invites colleagues via email on the "Overview" page.
3.  **Execution:** Team populates the Lit Review, adds Formulas, and embeds Python Code.
4.  **Completion:** Sections are marked "Finalized" one by one.
5.  **Export:** BibTeX is generated for citation managers.

### Flow 2: The Verification Loop
1.  **Sign Up:** New user registers as a Lecturer.
2.  **Request:** User goes to Settings → "Affiliation" → Selects "University of Indonesia".
3.  **Review:** The University Admin logs in → Sees badge "1 Pending Request".
4.  **Decision:** Admin reviews profile → Clicks "Verify".
5.  **Result:** Lecturer gets a "Verified" badge and their stats contribute to the University's dashboard.

---

## 🚀 Installation & Setup

Follow these steps to run AndRPaid locally.

### Prerequisites
* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL

### 1. Clone the Repository
```bash
git clone [https://github.com/your-username/andrpaid.git](https://github.com/your-username/andrpaid.git)
cd andrpaid
```

### 2. Install Dependencies
Install backend and frontend packages.
```bash
composer install
npm install
```

### 3. Environment Setup
Duplicate the example environment file and generate the application key.
```bash
copy .env.example .env
php artisan key:generate
```

### 4. Database Configuration
Open the `.env` file in your text editor and set your database credentials. Make sure to create a database named `andrpaid` (or your preferred name) in your MySQL server first.

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=andrpaid
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations & Seeders
Create the database tables and populate them with dummy data (Users, Universities, Lecturers).
```bash
php artisan migrate
php artisan db:seed
```

### 6. Build Frontend Assets
```bash
npm run build
# Or for development with hot reload:
npm run dev
```

### 7. Start the Server
```bash
php artisan serve
```

Access the application at: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔮 Future Roadmap
* **AI Integration:** Auto-summarization of uploaded PDF literature using LLMs.
* **Real-time Notifications:** WebSockets (Pusher) for "User X edited this section" alerts.
* **Kanban Board:** Task assignment within the Workspace (To Do/In Progress/Done).
* **Grant Marketplace:** Universities posting funding opportunities directly to the dashboard.

---

## 🤝 Contribution

Contributions are welcome! Please fork the repository and create a pull request for any feature enhancements or bug fixes.

1.  Fork the Project
2.  Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3.  Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4.  Push to the Branch (`git push origin feature/AmazingFeature`)
5.  Open a Pull Request
