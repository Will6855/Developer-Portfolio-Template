# Symfony Developer Portfolio

## Overview
This is a modern, multilingual developer portfolio built with Symfony, designed to be easily customizable and adaptable to different personal profiles.

## Features
- 🌐 Multilingual Support (Translations)
- 📱 Responsive Design
- 🚀 Easy Customization
- 📧 Contact Form Integration

## Customization

### Personal Information
To personalize this portfolio, you'll need to modify the following files:

#### Core Information
- `src/Service/PortfolioService.php`: 
  - Update skills
  - Modify work experience
  - Edit education details
  - Add/modify basic personal information

#### Contact and Personal Details
- `src/Controller/HomeController.php`:
  - Configure mailing settings for contact form
  - Set birthdate for age display (optional: it depends if you keep it in 'about.paragraph.1')

#### Legal and Compliance
- `templates/home/legal_notice.html.twig`:
  - Update personal and business information
  - Modify legal terms and conditions
  - Add your specific legal disclaimers and contact information

#### SEO and Webmaster Configuration
- `public/robots.txt`: 
  - Modify crawling and indexing rules
  - Update disallow/allow directives
  - Set appropriate search engine directives

- `public/sitemap.xml`:
  - Update site URLs
  - Modify last modification dates
  - Add/remove pages as needed

#### Translations
All translatable content is managed in the `translations/` directory:
- `messages.en.yaml`: English translations
- `messages.fr.yaml`: French translations
- Add more language files as needed

## Setup Instructions

### Prerequisites
- PHP 8.1+
- Composer
- Symfony CLI

### Installation
1. Clone the repository
   ```bash
   git clone https://github.com/Will6855/developer-portfolio.git
   cd developer-portfolio
   ```

2. Install dependencies
   ```bash
   composer install
   ```

3. Configure environment variables
   - Copy `.env.example` to `.env`
   - Update configuration as needed

4. Run the application
   ```bash
   symfony server:start
   ```

## Deployment
- Can be easily deployed to platforms supporting Symfony
- Ensure all environment variables are properly configured

## Contributing
Feel free to fork and customize for your own use. Pull requests are welcome!

## License
This project is licensed under the [MIT License](LICENSE.md)