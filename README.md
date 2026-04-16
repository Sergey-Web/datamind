# Datamind Test Task

Проект реализует:
1. Импорт данных из CSV в MongoDB
2. Перенос данных в OpenSearch
3. Агрегацию данных (отчёт)

---

## Quick Start

### 1. Clone the repository
```bash
git clone <repo_url>
cd <project_folder>
```

### 2. Build and start containers
```bash
make build
make up
```

### 3. Install dependencies
```bash
make composer install
```

### 4. Check services
```md
— API: http://localhost
— MongoDB: localhost:27017
— OpenSearch: http://localhost:9200  
```
---
## Import data into MongoDB
```bash
docker compose run --rm php-cli php yii import/csv-file-to-mongo
```
---
## Create index in OpenSearch
```bash
docker compose run --rm php-cli php yii search/reindex
```
---
## Sync data from MongoDB to OpenSearch
```bash
docker compose run --rm php-cli php yii import/sync-mongo-to-elastic "firm,region,product_name,quantity"
```
### Available fields
You can pass **any combination of fields** from the list below:
```md
— firm  
— region  
— city  
— invoice_date  
— delivery_address  
— client_address  
— client_name  
— client_code  
— client_division_code  
— client_okpo  
— license  
— license_expire_date  
— product_code  
— barcode  
— product_name  
— morion_code  
— unit  
— manufacturer  
— supplier  
— quantity  
— warehouse  
```
---
## Aggregation (Report)
```bash
docker compose run --rm php-cli php yii report/sum-quantity-by-region-and-product
```