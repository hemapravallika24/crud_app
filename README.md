# Blog Posts Project

## Description
A simple PHP/MySQL blog application that allows users to view posts with **search** and **pagination** functionality. Each post displays the **author’s username**, content, and creation date.  

---

## Features
- List all blog posts from the database.  
- Search posts by **title** or **content**.  
- Pagination for navigating posts.  
- Displays the **author username** for each post.  
- Clean layout using basic CSS.  

---

## Database Setup

1. **Create the database**
```sql
CREATE DATABASE IF NOT EXISTS blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE blog;
