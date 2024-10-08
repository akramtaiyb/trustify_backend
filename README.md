# Trustify

Trustify is a fact-checking platform that allows users to publish information, vote on its accuracy, and comment. The goal is to reduce the spread of misinformation using a community voting system and a reputation-based mechanism.

## Features

- Users can publish articles
- Vote on articles to classify them as "real" or "fake"
- Comment on articles
- AI-powered sentiment analysis on comments to help classify publications
- User reputation system based on their interactions
- Users can be ranked as experts once they reach a certain reputation score
- Expert votes have more weight in determining article classifications

## Screenshots

### Homepage
![Homepage](/screenshots/home.png)

### Journal Page
![Journal Page](/screenshots/journal_1.png)
![Journal Page](/screenshots/journal_2.png)

### Profile Page
![Profile Page](/screenshots/profile.png)

## Use Cases

1. **Sign Up and Login**
   - Users can sign up and log in to access all platform features.

2. **Publish Articles**
   - Once logged in, users can publish articles by providing a title and content.

3. **Vote on Articles**
   - Users can vote to classify an article as "real" or "fake." Expert users have more weight in their votes.

4. **Comment on Articles**
   - Users can comment on articles to discuss the content and provide additional evidence.

5. **Reputation System**
   - Users gain reputation points based on their interactions on the platform. Once a user reaches 1000 points, they become an expert.

## Notes on Gemini API Integration

1. **Gemini API Key for Seeders**
   - A Gemini API key is required to populate the database with realistic seed data. Since the platform is still in its early stages, we lack enough real user-generated content to train our models or provide a meaningful test experience. The API helps generate data for articles, comments, and user interactions, mimicking a realistic dataset for development and testing purposes.

2. **Gemini API for NLP**
   - We are also using the Gemini API to enhance the natural language processing (NLP) capabilities, particularly for comment analysis. The lack of large-scale real data requires us to rely on API-generated content to simulate real-world scenarios and improve sentiment analysis. This approach helps improve the accuracy of our AI-driven comment sentiment classification.

### Adding the Gemini API Key

In your `.env` file, add the following:
    ```bash
    GEMINI_API_KEY=your_api_key_here
    ```

## Project Setup Instructions

### Prerequisites

- Node.js (v18.19.1)
- npm (v10.2.4) or yarn
- PHP v8.1
- Composer v2.4.1
- MySQL v8.0

### Starting the Back-End

1. Clone the repository:
    ```bash
    git clone https://github.com/akramtaiyb/trustify_backend.git
    cd trustify_backend
    ```

2. Set up the environment:
    ```bash
    cp .env.example .env
    ```
   Update the `.env` file with your database details and the Gemini API key.

3. Install PHP dependencies:
    ```bash
    composer install
    ```

4. Generate the application key:
    ```bash
    php artisan key:generate
    ```

5. Run the migrations and seeders:
    ```bash
    php artisan migrate --seed
    ```

6. Start the development server:
    ```bash
    php artisan serve
    ```

7. Reclassify publications:
   ```bash
   php artisan publications:recalculate-scores
   ```

### Starting the Front-End

1. Clone the repository:
    ```bash
    git clone https://github.com/akramtaiyb/trustify_frontend.git
    ```

2. Navigate to the front-end folder:
    ```bash
    cd trustify_frontend
    ```

3. Install dependencies:
    ```bash
    npm install
    ```
   or
    ```bash
    yarn install
    ```

4. Start the development server:
    ```bash
    npm run dev
    ```
   or
    ```bash
    yarn run dev
    ```

## Login Credentials for Testing

Use the following credentials to log in and test the application:

- **Email**: test_account@trustify.com
- **Password**: password
