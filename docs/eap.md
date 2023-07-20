# EAP: Architecture Specification and Prototype

## A7: High-level architecture. Privileges. Web resources specification

This artifact describes the architecture of the web application that will be constructed, including a list of resources, their characteristics, and the JSON response format. Using YAML, this specification adheres to the OpenAPI standard.

### 1. Overview

| Identificator | Name                   | Description
| -------------------- | ---------------------- | -------------------------- |
| M01 | Authentication and user’s profiles | Web resources associated with user’s authentication and access to user’s profile. Includes the following features: login/logout, registration and edit profile.|
| M02 | Products |Web resources associated with the online store products. Includes the following features: add new products, remove products, edit products, search products, list products and view products |
| M03 | Reviews and Wishlists |Web resources associated with reviews and wish lists. Includes the following features: add reviews, remove reviews, edit reviews and add or remove products from the wish list, view products from the wishlist.|
| M04 | Orders and Carts | Web resources associated with product orders and carts. Includes the following features: add a product to cart, remove a product from cart, edit product quantity in cart, checkout cart.|
| M05 |User Administration and Information Pages |Web resources associated with user management features, that includes: search, block and unblock users, view users profiles and their purchase history. Web resources associated with information pages include: view and edit contact, about and faq pages.|


### 2. Permissions

| Identificator | Name                   | Description
| -------------------- | ---------------------- | -------------------------- |
| PUB | Public | Non-authenticated user without privileges.|
| USR | User   | Authenticated user with authenticated privileges.|
| OWN | Owner  | Authenticated user that owns an information and has its own privileges about that information(e.g.: own profile, own reviews).|
| ADM | Administrator |Authenticated user with system administrator privileges.|



### 3. OpenAPI Specification

OpenAPI specification in YAML format to describe the web application's web resources.

[Grab n' Build OpenAPI Specification](https://git.fe.up.pt/lbaw/lbaw2122/lbaw2175/-/blob/main/a7_openapi.yaml)


```yaml
openapi: 3.0.0

info:
 version: '0.1'
 title: 'LBAW GrabNBuild Web API'
 description: 'Web Resources Specification (A7) for GrabNBuild'
 
servers:
- url: http://lbaw.fe.up.pt
  description: Production server

externalDocs:
  description: Find more info here.
  url: https://web.fe.up.pt/~ssn/wiki/teach/lbaw/medialib/a07

tags:
 - name: 'M01: Authentication and Individual Profile'
 - name: 'M02: Products'
 - name: 'M03: Reviews and Wish list'
 - name: 'M04: Orders'
 - name: 'M05: User Administration and Information Pages'
 
 
paths:
 /login:
   get:
     operationId: R101
     summary: 'R101: Login Form'
     description: 'Provide login form. Access: PUB'
     tags:
       - 'M01: Authentication and Individual Profile'
     responses:
       '200':
         description: 'Ok. Show Log-in UI'
   post:
     operationId: R102
     summary: 'R102: Login Action'
     description: 'Processes the login form submission. Access: PUB'
     tags:
       - 'M01: Authentication and Individual Profile'

     requestBody:
       required: true
       content:
         application/x-www-form-urlencoded:
           schema:
             type: object
             properties:
               email:          # <!--- form field name
                 type: string
               password:    # <!--- form field name
                 type: string
             required:
                  - email
                  - password

     responses:
       '302':
         description: 'Redirect after processing the login credentials.'
         headers:
           Location:
             schema:
               type: string
             examples:
               302Success:
                 description: 'Successful authentication. Redirect to home page.'
                 value: '/'
               302Error:
                 description: 'Failed authentication. Redirect to login form.'
                 value: '/login'
        
 /logout:
   get:
     operationId: R103
     summary: 'R103: Logout Action'
     description: 'Logout the current authenticated user. Access: USR, ADM'
     tags:
       - 'M01: Authentication and Individual Profile'
     responses:
       '302':
         description: 'Redirect after processing logout.'
         headers:
           Location:
             schema:
               type: string
             examples:
               302Success:
                 description: 'Successful logout. Redirect to login form.'
                 value: '/login'

 /register:
   get:
     operationId: R104
     summary: 'R104: Register Form'
     description: 'Provide new user registration form. Access: PUB'
     tags:
       - 'M01: Authentication and Individual Profile'
     responses:
       '200':
         description: 'Ok. Show Sign-Up UI'

   post:
     operationId: R105
     summary: 'R105: Register Action'
     description: 'Processes the new user registration form submission. Access: PUB'
     tags:
       - 'M01: Authentication and Individual Profile'

     requestBody:
       required: true
       content:
         application/x-www-form-urlencoded:
           schema:
             type: object
             properties:
               name:
                 type: string
               email:
                 type: string
               picture:
                 type: string
                 format: binary
             required:
                - email
                - password

     responses:
       '302':
         description: 'Redirect after processing the new user information.'
         headers:
           Location:
             schema:
               type: string
             examples:
               302Success:
                 description: 'Successful authentication. Redirect to home page.'
                 value: '/'
               302Failure:
                 description: 'Failed authentication. Redirect to register form.'
                 value: '/register'

 /users:
   get:
     operationId: R106
     summary: 'R106: View user profile'
     description: 'Show the individual user profile. Access: USR'
     tags:
       - 'M01: Authentication and Individual Profile'

     parameters:
       - in: path
         name: id
         schema:
           type: integer
         required: true

     responses:
       '200':
         description: 'Ok. Show User Profile UI'
         
 /users/edit/{id}:
   post:
     operationId: R107
     summary: 'R107: Edit Action'
     description: 'Processes the new user profile info form submission. Access: OWN'
     tags:
       - 'M01: Authentication and Individual Profile'

     requestBody:
       required: true
       content:
         application/x-www-form-urlencoded:
           schema:
             type: object
             properties:
               name:
                 type: string
               picture:
                 type: string
                 format: binary

     responses:
       '302':
         description: 'Redirect after processing the new user information.'
         headers:
           Location:
             schema:
               type: string
             examples:
               302Success:
                 description: 'Successful authentication. Redirect to user profile.'
                 value: '/users'
               302Failure:
                 description: 'Failed authentication. Redirect to user profile.'
                 value: '/users'

 /api/products:
    get:
      operationId: R202
      summary: 'R202: Search Products API' #different search functions per type of product? since different specs
      description: 'Searches for products and returns the results as JSON. Access: PUB.'

      tags: 
        - 'M02: Products'

      parameters:
        - in: query
          name: query
          description: String to use for full-text search
          schema:
            type: string
          required: false
        - in: query
          name: price
          description: Float with the product price
          schema:
            type: number
          required: false
        - in: query
          name: classification
          description: Integer corresponding to the product classification
          schema:
            type: integer
          required: false

      responses:
        '200':
          description: Success
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    id:
                      type: string
                    title:
                      type: string
                    #obs:
                    #  type: string
                    #year:
                    #  type: string
                    #owner:
                    #  type: string
                    #type:
                    #  type: string

 /api/cpu:
    get:
      operationId: R203
      summary: 'R203: Search CPU API' 
      description: 'Searches for CPUs and returns the results as JSON. Access: PUB.'

      tags: 
        - 'M02: Products'

      parameters:
        - in: query
          name: query
          description: String to use for full-text search
          schema:
            type: string
          required: false
        - in: query
          name: basefreq
          description: Float with the basefreq
          schema:
            type: number
          required: false
        - in: query
          name: turbofreq
          description: Float with the turbofreq
          schema:
            type: number
          required: false
        - in: query
          name: socket
          description: String for socket
          schema:
            type: string
          required: false
        - in: query
          name: threads
          description: Integer corresponding to threads
          schema:
            type: integer
          required: false
        - in: query
          name: cores
          description: Integer corresponding to cores
          schema:
            type: integer
          required: false
        - in: query
          name: price
          description: Float with the product price
          schema:
            type: number
          required: false
        - in: query
          name: classification
          description: Integer corresponding to the product classification
          schema:
            type: integer
          required: false

      responses:
        '200':
          description: Success
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                  
 /api/gpu:
    get:
      operationId: R204
      summary: 'R204: Search GPU API' 
      description: 'Searches for GPUs and returns the results as JSON. Access: PUB.'

      tags: 
        - 'M02: Products'

      parameters:
        - in: query
          name: query
          description: String to use for full-text search
          schema:
            type: string
          required: false
        - in: query
          name: memory
          description: Int with memory
          schema:
            type: integer
          required: false
        - in: query
          name: coreclock
          description: Int with the coreclock
          schema:
            type: integer
          required: false
        - in: query
          name: boostclock
          description: Int for boostclock
          schema:
            type: integer
          required: false
        - in: query
          name: boostclock
          description: Integer corresponding to boostclock
          schema:
            type: integer
          required: false
        - in: query
          name: hdmiports
          description: Integer corresponding to hdmi ports
          schema:
            type: integer
          required: false
        - in: query
          name: dispports
          description: Integer corresponding to display ports
          schema:
            type: integer
          required: false
        - in: query
          name: price
          description: Float with the product price
          schema:
            type: number
          required: false
        - in: query
          name: classification
          description: Integer corresponding to the product classification
          schema:
            type: integer
          required: false

      responses:
        '200':
          description: Success
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                  
 /api/motherboard:
    get:
      operationId: R205
      summary: 'R205: Search Motherboards API' 
      description: 'Searches for motherboards and returns the results as JSON. Access: PUB.'

      tags: 
        - 'M02: Products'

      parameters:
        - in: query
          name: query
          description: String to use for full-text search
          schema:
            type: string
          required: false
        - in: query
          name: socket
          description: String for socket
          schema:
            type: string
          required: false
        - in: query
          name: type
          description: String for motherboardtype
          schema:
            type: string
          required: false
        - in: query
          name: price
          description: Float with the product price
          schema:
            type: number
          required: false
        - in: query
          name: classification
          description: Integer corresponding to the product classification
          schema:
            type: integer
          required: false

      responses:
        '200':
          description: Success
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                  
 /api/storage:
    get:
      operationId: R206
      summary: 'R206: Search Storage API' 
      description: 'Searches for storage and returns the results as JSON. Access: PUB.'

      tags: 
        - 'M02: Products'

      parameters:
        - in: query
          name: query
          description: String to use for full-text search
          schema:
            type: string
          required: false
        - in: query
          name: capacity
          description: Int for storage capacity
          schema:
            type: integer
          required: false
        - in: query
          name: type
          description: String for storagetype
          schema:
            type: string
          required: false
        - in: query
          name: price
          description: Float with the product price
          schema:
            type: number
          required: false
        - in: query
          name: classification
          description: Integer corresponding to the product classification
          schema:
            type: integer
          required: false

      responses:
        '200':
          description: Success
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                  
 /api/case:
    get:
      operationId: R207
      summary: 'R207: Search Case API' 
      description: 'Searches for cases and returns the results as JSON. Access: PUB.'

      tags: 
        - 'M02: Products'

      parameters:
        - in: query
          name: query
          description: String to use for full-text search
          schema:
            type: string
          required: false
        - in: query
          name: color
          description: String for color
          schema:
            type: string
          required: false
        - in: query
          name: weight
          description: String for weight
          schema:
            type: string
          required: false
        - in: query
          name: type
          description: String for case type
          schema:
            type: string
          required: false
        - in: query
          name: price
          description: Float with the product price
          schema:
            type: number
          required: false
        - in: query
          name: classification
          description: Integer corresponding to the product classification
          schema:
            type: integer
          required: false

      responses:
        '200':
          description: Success
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                  
 /api/cooler:
    get:
      operationId: R208
      summary: 'R208: Search Cooler API' 
      description: 'Searches for coolers and returns the results as JSON. Access: PUB.'

      tags: 
        - 'M02: Products'

      parameters:
        - in: query
          name: query
          description: String to use for full-text search
          schema:
            type: string
          required: false
        - in: query
          name: type
          description: String for cooler type
          schema:
            type: string
          required: false
        - in: query
          name: price
          description: Float with the product price
          schema:
            type: number
          required: false
        - in: query
          name: classification
          description: Integer corresponding to the product classification
          schema:
            type: integer
          required: false

      responses:
        '200':
          description: Success
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                  
 /api/powersupply:
    get:
      operationId: R209
      summary: 'R209: Search PowerSupply API' 
      description: 'Searches for power supplies and returns the results as JSON. Access: PUB.'

      tags: 
        - 'M02: Products'

      parameters:
        - in: query
          name: query
          description: String to use for full-text search
          schema:
            type: string
          required: false
        - in: query
          name: wattage
          description: Int for wattage
          schema:
            type: integer
          required: false
        - in: query
          name: certification
          description: String for certification
          schema:
            type: string
          required: false
        - in: query
          name: type
          description: String for supply type
          schema:
            type: string
          required: false
        - in: query
          name: price
          description: Float with the product price
          schema:
            type: number
          required: false
        - in: query
          name: classification
          description: Integer corresponding to the product classification
          schema:
            type: integer
          required: false

      responses:
        '200':
          description: Success
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:


 #addproduct beggining
 /addprod:
    post:
      operationId: R212
      summary: 'R212: Add Generic Product' 
      description: 'Adds a generic product, redirects to define specifics. Access: ADM.'

      tags: 
        - 'M02: Products'

      parameters:
        - in: query
          name: name
          description: Product name
          schema:
            type: string
          required: true
        - in: query
          name: price
          description: Float with the product price
          schema:
            type: number
          required: true
        - in: query
          name: brand
          description: Product brand
          schema:
            type: string
          required: true
        - in: query
          name: image
          description: Product image
          schema:
            type: string
          required: true
        - in: query
          name: size
          description: Product size
          schema:
            type: string
          required: true
        - in: query
          name: stock
          description: Starting stock
          schema:
            type: integer
          required: true
        - in: query
          name: type
          description: Product type
          schema:
            type: string
          required: true

      responses:
        '302':
          description: 'Redirect after setting basic prod features'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful authentication. Redirect to prod.'
                  value: '/addprod/{type}'
                302Failure:
                  description: 'Failed authentication. Redirect addprod.'
                  value: '/addprod'

 /addprod/cpu:
    post:
      operationId: R213
      summary: 'R213: Add CPU' 
      description: 'Sets CPU features after setting initally prod features. Access: ADM.'

      tags: 
        - 'M02: Products'

      parameters:
        - in: query
          name: basefreq
          description: Float with the basefreq
          schema:
            type: number
          required: true
        - in: query
          name: turbofreq
          description: Float with the turbofreq
          schema:
            type: number
          required: true
        - in: query
          name: socket
          description: String for socket
          schema:
            type: string
          required: true
        - in: query
          name: threads
          description: Integer corresponding to threads
          schema:
            type: integer
          required: true
        - in: query
          name: cores
          description: Integer corresponding to cores
          schema:
            type: integer
          required: true

      responses:
        '200':
          description: Success
                  
 /addprod/gpu:
    post:
      operationId: R214
      summary: 'R214: Add GPU Product' 
      description: 'Sets GPU features after setting initally prod features. Access: ADM.'

      tags: 
        - 'M02: Products'

      parameters:
        - in: query
          name: memory
          description: Int with memory
          schema:
            type: integer
          required: true
        - in: query
          name: coreclock
          description: Int with the coreclock
          schema:
            type: integer
          required: true
        - in: query
          name: boostclock
          description: Int for boostclock
          schema:
            type: integer
          required: true
        - in: query
          name: boostclock
          description: Integer corresponding to boostclock
          schema:
            type: integer
          required: true
        - in: query
          name: hdmiports
          description: Integer corresponding to hdmi ports
          schema:
            type: integer
          required: true
        - in: query
          name: dispports
          description: Integer corresponding to display ports
          schema:
            type: integer
          required: true

      responses:
        '200':
          description: Success
                  
 /addprod/motherboard:
    post:
      operationId: R215
      summary: 'R215: Add Motherboard Product' 
      description: 'Sets Motherboard features after setting initally prod features. Access: ADM.'

      tags: 
        - 'M02: Products'

      parameters:
        - in: query
          name: socket
          description: String for socket
          schema:
            type: string
          required: true
        - in: query
          name: type
          description: String for motherboardtype
          schema:
            type: string
          required: true

      responses:
        '200':
          description: Success
                  
 /addprod/storage:
    post:
      operationId: R216
      summary: 'R216: Adds Storage Features after Base Prod Specification' 
      description: 'Sets Storage features after setting initally prod features. Access: ADM.'

      tags: 
        - 'M02: Products'

      parameters:
        - in: query
          name: capacity
          description: Int for storage capacity
          schema:
            type: integer
          required: true
        - in: query
          name: type
          description: String for storagetype
          schema:
            type: string
          required: true

      responses:
        '200':
          description: Success
                  
 /addprod/case:
    post:
      operationId: R217
      summary: 'R217: Add Case Product' 
      description: 'Sets Case features after setting initally prod features. Access: ADM.'

      tags: 
        - 'M02: Products'

      parameters:
        - in: query
          name: color
          description: String for color
          schema:
            type: string
          required: true
        - in: query
          name: weight
          description: String for weight
          schema:
            type: string
          required: true
        - in: query
          name: type
          description: String for case type
          schema:
            type: string
          required: true

      responses:
        '200':
          description: Success
                  
 /addprod/cooler:
    post:
      operationId: R218
      summary: 'R218: Add Cooler Product' 
      description: 'Sets Cooler features after setting initally prod features. Access: ADM.'

      tags: 
        - 'M02: Products'

      parameters:
        - in: query
          name: type
          description: String for cooler type
          schema:
            type: string
          required: true

      responses:
        '200':
          description: Success

 /addprod/powersupply:
    post:
      operationId: R219
      summary: 'R219: Add PowerSupply Product' 
      description: 'Sets CPU features after setting initally prod features. Access: ADM.'

      tags: 
        - 'M02: Products'

      parameters:
        - in: query
          name: wattage
          description: Int for wattage
          schema:
            type: integer
          required: true
        - in: query
          name: certification
          description: String for certification
          schema:
            type: string
          required: true
        - in: query
          name: type
          description: String for supply type
          schema:
            type: string
          required: true

      responses:
        '200':
          description: Success
#addproduct end

 /removeprod:
     get:
       operationId: R220
       summary: 'R220: Removes Given Product' 
       description: 'Removes product given by ID. Access: ADM.'

       tags: 
         - 'M02: Products'

       parameters:
         - in: query
           name: id
           description: Int for id
           schema:
             type: number
           required: true

       responses:
         '200':
           description: Success

 /products/{id}:
   get:
     operationId: R210
     summary: 'R210: Product Info'
     description: 'Provides info on specified product. Access: PUB'
     tags:
       - 'M02: Products'
     responses:
       '200':
         description: 'Ok. Show Prod Info'

 /addreview:
    post:
     operationId: R301
     summary: 'R301: Add Review to product'
     description: 'Adds review to specified product: Access: OWN'
     tags:
       - 'M03: Reviews and Wish list'
       
     requestBody:
       required: true
       content:
         application/x-www-form-urlencoded:
           schema:
             type: object
             properties:
               stars:          
                 type: int
               text:    
                 type: string
             required:
                  - stars
                  - text

     responses:
       '302':
         description: 'Redirect after processing the review.'
         headers:
           Location:
             schema:
               type: string
             examples:
               302Success:
                 description: 'Successful authentication. Redirect to prod.'
                 value: '/products/{id}'
               302Failure:
                 description: 'Failed authentication. Redirect to prod.'
                 value: '/products/{id}'
            
 /editreview:
    post:
     operationId: R302
     summary: 'R302: Edit Product Review'
     description: 'Edits review given to specified product: Access: OWN'
     tags:
       - 'M03: Reviews and Wish list'
       
     requestBody:
       required: true
       content:
         application/x-www-form-urlencoded:
           schema:
             type: object
             properties:
               stars:          
                 type: int
               text:    
                 type: string
             required:
                  - stars
                  - text

     responses:
       '302':
         description: 'Redirect after processing the review.'
         headers:
           Location:
             schema:
               type: string
             examples:
               302Success:
                 description: 'Successful authentication. Redirect to prod.'
                 value: '/products/{id}'
               302Failure:
                 description: 'Failed authentication. Redirect to prod.'
                 value: '/products/{id}'

 /removereview:
    post:
     operationId: R303
     summary: 'R303: Remove Review from product'
     description: 'Removes review from specified product: Access: OWN'
     tags:
       - 'M03: Reviews and Wish list'

     responses:
       '302':
         description: 'Redirect after processing the review.'
         headers:
           Location:
             schema:
               type: string
             examples:
               302Success:
                 description: 'Successful authentication. Redirect to prod.'
                 value: '/products/{id}'
               302Failure:
                 description: 'Failed authentication. Redirect to prod.'
                 value: '/products/{id}'

 /addtowishlist:
    post:
     operationId: R304
     summary: 'R304: Add Product to Wishlist'
     description: 'Adds Product to User Wishlist: Access: USR'
     tags:
       - 'M03: Reviews and Wish list'
       
     responses:
       '200':
         description: Success

 /removefromwishlist:
    post:
     operationId: R305
     summary: 'R305: Remove Product from Wishlist'
     description: 'Removes Product from User Wishlist: Access: OWN'
     tags:
       - 'M03: Reviews and Wish list'
       
     responses:
       '200':
         description: Success

 /viewwishlist:
    get:
     operationId: R306
     summary: 'R306: List products in wishlist'
     description: 'Lists Products from User Wishlist: Access: OWN'
     tags:
       - 'M03: Reviews and Wish list'
       
     responses:
       '200':
         description: Success
         content:
           application/json:
             schema:
               type: array
               items:
                 type: object
                 properties:

 /users/cart:
   get:
     operationId: R400
     summary: 'R400: Shows given users cart' 
     description: 'Show cart. Access: OWN.'

     tags: 
       - 'M04: Orders'

     responses:
       '200':
         description: Success
         content:
           application/json:
             schema:
               type: array
               items:
                 type: object
                 properties:

 /cart/add:
   post:
     operationId: R401
     summary: 'R401: Adds product by ID to cart' 
     description: 'Adds given product to cart. Access: PUB.'

     tags: 
       - 'M04: Orders'

     parameters:
       - in: query
         name: id
         description: Product ID
         schema:
           type: number
         required: true
       - in: query
         name: quantity
         description: Product quantity
         schema:
           type: integer
         required: true

     responses:
       '200':
         description: Success

 /cart/update:
   post:
     operationId: R402
     summary: 'R402: Updates quantity of a product in cart' 
     description: 'Update product in cart. Access: OWN.'

     tags: 
       - 'M04: Orders'

     parameters:
       - in: query
         name: id
         description: Product ID
         schema:
           type: number
         required: true
       - in: query
         name: quantity
         description: Product quantity
         schema:
           type: integer
         required: true

     responses:
       '200':
         description: Success

 /cart/remove:
   post:
     operationId: R403
     summary: 'R403: Removes a product in cart' 
     description: 'Remove product in cart. Access: OWN.'

     tags: 
       - 'M04: Orders'

     parameters:
       - in: query
         name: id
         description: Product ID
         schema:
           type: number
         required: true

     responses:
       '200':
         description: Success

 /cart/checkout:
   post:
     operationId: R404
     summary: 'R404: Checkouts cart of Products' 
     description: 'Checkout cart of products. Access: OWN.'

     tags: 
       - 'M04: Orders'

     responses:
       '200':
         description: Success

 /api/usersearch: #admins could see blocked users, how?
   get:
     operationId: R501
     summary: 'R501: Search User accounts' 
     description: 'Lists all users. Access: PUB.'

     tags: 
       - 'M05: User Administration and Information Pages'

     parameters:
       - in: query
         name: name
         description: User name
         schema:
           type: string
         required: false
       - in: query
         name: email
         description: User email
         schema:
           type: integer
         required: false

     responses:
       '200':
         description: Success
         content:
           application/json:
             schema:
               type: array
               items:
                 type: object
                 properties:
                  

 /blockusr:
   post:
     operationId: R502
     summary: 'R502: Block User'
     description: 'Blocks Specified User. Access: ADM'
     tags:
       - 'M05: User Administration and Information Pages'
     responses:
       '200':
         description: 'Ok. Block'
 /unblockusr:
   post:
     operationId: R503
     summary: 'R503: Unblock User'
     description: 'Unblocks Specified User. Access: ADM'
     tags:
       - 'M05: User Administration and Information Pages'
     responses:
       '200':
         description: 'Ok. Unblock'
 /viewhistory:
   post:
     operationId: R504
     summary: 'R504: View User Purchase History'
     description: 'Shows purchase history of specified user. Access: OWN, ADM'
     tags:
       - 'M05: User Administration and Information Pages'
     responses:
       '200':
         description: 'Ok. Show'
         
 /viewcontact:
   get:
     operationId: R505
     summary: 'R505: View Contact Page'
     description: 'View the Contact Page. Access: PUB'
     tags:
       - 'M05: User Administration and Information Pages'
     responses:
       '200':
         description: 'Ok. Show'
         
 /viewabout:
   get:
     operationId: R506
     summary: 'R506: View About Page'
     description: 'View the About Page. Access: PUB'
     tags:
       - 'M05: User Administration and Information Pages'
     responses:
       '200':
         description: 'Ok. Show'

 /viewfaq:
   get:
     operationId: R507
     summary: 'R507: View Faq Page'
     description: 'View the Faq Page. Access: PUB'
     tags:
       - 'M05: User Administration and Information Pages'
     responses:
       '200':
         description: 'Ok. Show'


```

---

## A8: Vertical prototype

The Vertical Prototype includes the implementation of the features marked as necessary in the common and theme requirements documents. This artefact aims to validate the architecture presented.
The implementation is based on the LBAW Framework and includes work on all layers of the architecture of the solution to implement: user interface, business logic and data access. 

### 1. Implemented Features

#### 1.1. Implemented User Stories


| User Story  | Name                   | Priority                   | Description                   |
| -------------------- | ---------------------- | -------------------------- | ----------------------------- |
| US01 | Home | High | As a _User_, I want to access the Home page, so that I can be introduced to the website. |
| US02 | FAQ | High |As a _User_, I want to access the FAQ page, so that I can look for an answer to a question I might have.|
| US03 | Contacts | High |As a _User_, I want to access the Contacts Page, so that I can easily retrieve the website founders’ contacts.|
| US04  | About | High |As a _User_, I want to access the About page, so that I can get to know what the website is focused on and how it was created.|
| US05 | Search using the Navigation Menu | High |As a _User_, I want to search for a group of products using the navigation menu so that I can easily find what I’m looking for. |
| US06 | Searching using the Search Bar | High | As a _User_, I want to search for products using the search bar, so that I can use keywords to find what I’m looking for more accurately.|
| US09 | Access product page | High |As a _User_, I want to access a product page, so that I can get access to detailed information and specifications
| US31 | Profile |High |As an _Authenticated User_, I want to view and edit my profile, to keep track of my information. |
| US37 | Sign-out |High |As an _Authenticated User_, I want to log out of my account, so that I can close it out. |
| US38     | Check Purchase History    | High     | As an _Authenticated User_, I want to view the history of my previous purchase, so that I can keep track of my expenditures in the system.     |
| US41 | Sign-In | High | As a _Guest_, I want  to login into my account, so that I can get authenticated user privilege.
| US42 | Sign-Up | High | As a _Guest_, I want to create an account, so that I’m able to authenticate into the system. |

...

#### 1.2. Implemented Web Resources


**Module M01: Authentication and User’s profiles**

| Web Resource Reference | URL                            |
| ---------------------- | ------------------------------ |
| R101: Login Form | GET /login |
| R102: Login Action | POST /login |
| R103: Logout Action | GET /logout |
| R104: Register Form | GET /register |
| R105: Register Action | POST /register |
| R106: View user profile |  GET /users |
| R107: Delete user profile | DELETE /users/ |
| R108: Edit action | POST /users/edit/{id} |

**Module M02: Products**

| Web Resource Reference | URL                            |
| ---------------------- | ------------------------------ |
| R203: Product Info | GET /products{id} |

**Module M03: Reviews and Wish lists** 

| Web Resource Reference | URL                            |
| ---------------------- | ------------------------------ |
| R304: Add product to wishlist | PUT /users/wishlist/{product_id} |
| R305: Remove product from wishlist | DELETE users/wishlist/{product_id} |
| R306: List products in wishlist | GET /users/wishlist/ |
| R307: Empty wishlist | DELETE /users/wishlist/ |

**Module M04: Orders and Carts**

| Web Resource Reference | URL                            |
| ---------------------- | ------------------------------ |
| R400: Shows given users cart | GET /users/cart |
| R401: Empty given users cart | DELETE /users/cart |
| R402: Adds a product in cart | PUT /users/cart/{products_id} |
| R403: Removes a product in cart | DELETE /users/cart/{products_id} |
| R404: Increment quantity of a product in cart | POST /users/cart/{products_id}/increment
| R405: Decrement quantity of a product in cart | POST /users/cart/{products_id}/decrement
| R406: Checkout cart of products | POST /users/cart/checkout

 **Module M05: User Administration and Information Pages**

| Web Resource Reference | URL                            |
| ---------------------- | ------------------------------ |
| R505: View Contact Page | GET /contacts |
| R506: View About Page   | GET /about |
| R507: View Faq Page     | GET /faq   |

### 2. Prototype

The prototype can be seen at [lbaw2175.lbaw.fe.up.pt](http://lbaw2175.lbaw.fe.up.pt)

Use the following credentials
* Admin 
    * Email - up201907716@up.pt
    * Password - 123456789
* Regular user
    * Email - vova10000@bukan.es
    * Password - firstUserPass

The code is available [here](https://git.fe.up.pt/lbaw/lbaw2122/lbaw2175/-/tree/EAP)

---


## Revision history

Changes made to the first submission:
1. Item 1

***
GROUP2175, 4/11/2022

* Carlos Veríssimo, up201907716 (Editor)
* Duarte Sardão, up201905497
* Nuno Jesus, up201905477
* Tomás Torres, up201800700 