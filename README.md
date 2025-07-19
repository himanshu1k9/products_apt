🛍️ Laravel Product Management API
This is a simple Laravel-based CRUD application for managing products using Blade, Bootstrap, jQuery, and AJAX.

🔧 Features
View all products (status = 0)

Add a new product via modal form

Edit an existing product

Delete a product

AJAX-based form submission and deletion

Bootstrap modal for add/edit

CSRF protection enabled

📁 Route Overview (routes/web.php)
Method	URI	Controller Method	Description
GET	/	index()	Show product list
GET	/store	store()	Open form for product
POST	/addproduct	create()	Store product data
GET	/getsingle/{id}	getSingleProduct()	Get product for edit
POST	/update/{id}	update()	Update product
POST	/destroy/{id}	destroy()	Delete product

💡 Notes
Ensure jQuery and Bootstrap are loaded in your view.

The add/edit modal is reused; mode is toggled via isEditMode.

On form success, the page redirects to / or the table is refreshed manually.

Product model is assumed to use status field for filtering.

🐞 Common Fixes
Replace ->all() with ->get() in Eloquent queries.

Avoid location.reload() in AJAX success callbacks — use window.location.href = "/" instead.

Always check CSRF token and matching route URLs.

