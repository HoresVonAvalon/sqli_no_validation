# === Dockerfile ===
FROM php:8.0-apache

# 1. System-Pakete aktualisieren und libsqlite3-dev installieren,
#    damit pdo_sqlite kompiliert werden kann:
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
      libsqlite3-dev \
      ca-certificates \
      unzip \
    && rm -rf /var/lib/apt/lists/*

# 2. PDO + PDO_SQLITE aktivieren
RUN docker-php-ext-install pdo pdo_sqlite

# 3. Arbeitsverzeichnis auf Apache-DocumentRoot setzen
WORKDIR /var/www/html

# 4. PHP-Skripte ins Web-Verzeichnis kopieren
COPY www/ /var/www/html/

# 5. Sicherstellen, dass das data-Verzeichnis existiert und Apache dort schreiben darf
RUN mkdir -p /var/www/html/data \
    && chown -R www-data:www-data /var/www/html/data

# 6. Standard-Apache-Port (80) wird expose-ed
EXPOSE 80
