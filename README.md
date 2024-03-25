# Laravel Project

## Getting Started

To get started with this project, follow these steps:

1. **Clone the Repository**: 
   ```bash
   git clone https://github.com/yourusername/Freelancer.git

2. **go inside the folder**: 
   ```bash
   cd Freelancer

3. **install vendor**: 
   ```bash
   composer update
4. **init .env file **: 
   ```bash
   cp .env .env.example
   
5. **generate key**: 
   ```bash
   php artisan key:generate
   
6. **migrate to database and seed**: 
   ```bash
   php artisan migrate --seed

7. **start the server**: 
   ```bash
   php artisan serve
