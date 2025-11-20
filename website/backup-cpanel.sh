#!/bin/bash

###############################################################################
# cPanel Backup Script - Automated Database & Files Backup
# For Laravel Application
###############################################################################

# ==================== CONFIGURATION ====================

# Project path (adjust this to your cPanel home directory)
PROJECT_PATH="/home/username/public_html"

# Backup destination path
BACKUP_PATH="/home/username/backups"

# Database credentials (from .env file)
DB_NAME="your_database_name"
DB_USER="your_database_user"
DB_PASS="your_database_password"
DB_HOST="localhost"

# Retention days (keep backups for X days)
RETENTION_DAYS=7

# Email notification (optional)
NOTIFY_EMAIL="your-email@domain.com"

# ==================== DO NOT EDIT BELOW THIS LINE ====================

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Timestamp
TIMESTAMP=$(date +"%Y-%m-%d_%H-%M-%S")
DATE=$(date +"%Y-%m-%d")

# Backup directories
DB_BACKUP_DIR="$BACKUP_PATH/database"
FILES_BACKUP_DIR="$BACKUP_PATH/files"
LOG_DIR="$BACKUP_PATH/logs"

# Log file
LOG_FILE="$LOG_DIR/backup_$DATE.log"

# Create backup directories if they don't exist
mkdir -p "$DB_BACKUP_DIR"
mkdir -p "$FILES_BACKUP_DIR"
mkdir -p "$LOG_DIR"

# ==================== FUNCTIONS ====================

log_message() {
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a "$LOG_FILE"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1" | tee -a "$LOG_FILE"
}

log_info() {
    echo -e "${YELLOW}[INFO]${NC} $1" | tee -a "$LOG_FILE"
}

# ==================== START BACKUP ====================

log_info "=========================================="
log_info "Starting Backup Process"
log_info "=========================================="

# ==================== DATABASE BACKUP ====================

log_info "Starting database backup..."

DB_FILENAME="backup_${DB_NAME}_${TIMESTAMP}.sql"
DB_FILEPATH="$DB_BACKUP_DIR/$DB_FILENAME"

# Dump database
mysqldump --user="$DB_USER" --password="$DB_PASS" --host="$DB_HOST" "$DB_NAME" > "$DB_FILEPATH" 2>> "$LOG_FILE"

if [ $? -eq 0 ]; then
    # Get file size
    DB_SIZE=$(du -h "$DB_FILEPATH" | cut -f1)
    log_success "Database backup created: $DB_FILENAME ($DB_SIZE)"

    # Compress the backup
    log_info "Compressing database backup..."
    gzip "$DB_FILEPATH"

    if [ $? -eq 0 ]; then
        COMPRESSED_SIZE=$(du -h "${DB_FILEPATH}.gz" | cut -f1)
        log_success "Database backup compressed: ${DB_FILENAME}.gz ($COMPRESSED_SIZE)"
    else
        log_error "Failed to compress database backup"
    fi
else
    log_error "Database backup failed!"
fi

# ==================== FILES BACKUP ====================

log_info "Starting files backup..."

FILES_FILENAME="backup_files_${TIMESTAMP}.tar.gz"
FILES_FILEPATH="$FILES_BACKUP_DIR/$FILES_FILENAME"

# Backup files (excluding vendor, node_modules, storage/logs, and previous backups)
cd "$PROJECT_PATH" || exit
tar -czf "$FILES_FILEPATH" \
    --exclude='vendor' \
    --exclude='node_modules' \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    --exclude='.git' \
    --exclude='backups' \
    . 2>> "$LOG_FILE"

if [ $? -eq 0 ]; then
    FILES_SIZE=$(du -h "$FILES_FILEPATH" | cut -f1)
    log_success "Files backup created: $FILES_FILENAME ($FILES_SIZE)"
else
    log_error "Files backup failed!"
fi

# ==================== CLEANUP OLD BACKUPS ====================

log_info "Cleaning up old backups (older than $RETENTION_DAYS days)..."

# Delete old database backups
DELETED_DB=$(find "$DB_BACKUP_DIR" -type f -name "*.sql.gz" -mtime +$RETENTION_DAYS -delete -print | wc -l)
log_info "Deleted $DELETED_DB old database backup(s)"

# Delete old file backups
DELETED_FILES=$(find "$FILES_BACKUP_DIR" -type f -name "*.tar.gz" -mtime +$RETENTION_DAYS -delete -print | wc -l)
log_info "Deleted $DELETED_FILES old file backup(s)"

# Delete old logs
find "$LOG_DIR" -type f -name "*.log" -mtime +30 -delete

# ==================== BACKUP SUMMARY ====================

log_info "=========================================="
log_info "Backup Summary"
log_info "=========================================="

# Count current backups
DB_BACKUP_COUNT=$(ls -1 "$DB_BACKUP_DIR"/*.sql.gz 2>/dev/null | wc -l)
FILES_BACKUP_COUNT=$(ls -1 "$FILES_BACKUP_DIR"/*.tar.gz 2>/dev/null | wc -l)

log_info "Database backups: $DB_BACKUP_COUNT"
log_info "Files backups: $FILES_BACKUP_COUNT"

# Disk usage
TOTAL_BACKUP_SIZE=$(du -sh "$BACKUP_PATH" | cut -f1)
log_info "Total backup size: $TOTAL_BACKUP_SIZE"

log_success "Backup process completed!"
log_info "=========================================="

# ==================== EMAIL NOTIFICATION (Optional) ====================

# Uncomment below to enable email notifications
# if [ ! -z "$NOTIFY_EMAIL" ]; then
#     SUBJECT="Backup Completed - $(date +'%Y-%m-%d')"
#     BODY="Backup completed successfully at $(date).\n\nDatabase backups: $DB_BACKUP_COUNT\nFiles backups: $FILES_BACKUP_COUNT\nTotal size: $TOTAL_BACKUP_SIZE\n\nCheck log file: $LOG_FILE"
#     echo -e "$BODY" | mail -s "$SUBJECT" "$NOTIFY_EMAIL"
# fi

exit 0
