# Recipe Finder

A modern, mobile-friendly recipe search and management application. Uses PHP + JSON data storage, no external dependencies required.

## Features
- 🔍 Search by recipe name, author, or ingredient (real-time filtering, match percentage)
- 📌 Popular/trending recipe list on the home page, sorting options:
  - Trending (likes + saves + made score)
  - Most liked, most saved, most made, best rated
- 👤 Profile page: my recipes, my favorites, my made recipes, my comments (image-supported cards)
- ❤️ Like, ⭐ favorite, 👨‍🍳 I made it, 🌟 rating, 💬 commenting
- 🥘 Add recipe and share “I made it” photo/note

## Getting Started
```bash
# Go to the project directory
cd /home/eray/Desktop/yemekSitesi

# Start the PHP local server (port 8000)
php -S localhost:8000
```
Go to `http://localhost:8000` in your browser.

## Directory
- `index.php` : Search box + popular/trending list
- `search.php` : Dynamic results page (JS filtering)
- `recipe.php` : Recipe details, likes/favorites/rating/comments
- `profile.php` : User recipes, favorites, made it, comments
- `add_recipe.php`, `i_made_it.php`, `login.php` : Form and authentication components
- `css/` : Page-based style files
- `js/` : Page-based JavaScript files (search, recipe, profile, index)
- `data/` : Recipe JSON data (`recipes.json`)
- `user_*.json`, `recipe_*.json` : Likes, favorites, comments, ratings, “I made it” records

## Configuration
- Default data storage: JSON files, no extra DB required.
- PHP 7+ is sufficient. No additional dependencies.

## Notes
- If you enter an image URL when adding a new recipe, it will be displayed on the cards.
- Author search matches the username or email address.
- “Trending” score: `(likes x2) + (saves x3) + views`. Recipes with more engagement appear at the top.

## Development Tips
- CSS/JS files are page-specific; use `css/global.css` for shared styles.
- To add new filters or sorting, extend the `getRecipesSorted` function in `functions.php`.
- Ensure the folder you're working in is writable, as write permissions are required for JSON files.






