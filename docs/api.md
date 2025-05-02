## API documentation
API documentation for speedcast.

## Table of contents
- **[Payload](#response-payload-format)**
- **[Routes](#routes)**


### Response payload format
```json
{
    "success": true,
    "data": {},
    "error": null
}
```

**success**
- `true` - Return only if everything okay. Http status `200`
- `false` - Return if http status is not `200`

**data**
- `{}` - Return for a single record.
- `[]` - Return for multiple records.
- `null` - Return when `success: false`.

**error**
- `null` - Return if success.
- `string` - Return if `success: falsse`.
---

### Routes
| Method | Description                | Route                            | Action                              |
|:-------| ---------------------------|----------------------------------|-------------------------------------|
| GET    | Get banner list            | `api/v1/banners`                 | [View](#get-banner-list)            |
| GET    | Get category list          | `api/v1/categories`              | [View](#get-category-list)          |
| GET    | Get companies lit          | `api/v1/companies`               | [View](#get-company-list)           |
| GET    | Get banners by category ID | `api/v1/categories/{id}/banners` | [View](#get-banners-by-category-id) |
---

### Get banner list

### Get category list

### Get banners by category ID

### Get company list