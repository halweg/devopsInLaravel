#!/bin/bash

set -e

BACKUP_FILENAME=$1
MYSQL_USER=$2
MYSQL_PASSWORD=$3

PROJECT_DIR="/var/www/html/posts"
BACKUP_DIR=$PROJECT_DIR"/api/storage/app/backup"

aws s3 cp s3://devops-with-laravel-backups/$BACKUP_FILENAME $PROJECT_DIR"/api/storage/app/backup.zip"
unzip -o $PROJECT_DIR"/api/storage/app/backup.zip" -d $BACKUP_DIR

php $PROJECT_DIR"/api/artisan" down

# Restore database
mysql -u$MYSQL_USER -p$MYSQL_PASSWORD posts < $BACKUP_DIR"/db-dumps/mysql-posts.sql"

# Copy the current files
mv $PROJECT_DIR"/api/.env" $PROJECT_DIR"/api/.env_before_restore"
mv $PROJECT_DIR"/api/storage/app/public" $PROJECT_DIR"/api/storage/app/public_before_restore"

# Restore old files from backup
mv $BACKUP_DIR"/"$PROJECT_DIR"/api/.env" $PROJECT_DIR"/api/.env"
mv $BACKUP_DIR"/"$PROJECT_DIR"/api/storage/app/public" $PROJECT_DIR"/api/storage/app/public"

php $PROJECT_DIR"/api/artisan" storage:link
php $PROJECT_DIR"/api/artisan" optimize:clear

php $PROJECT_DIR"/api/artisan" up
