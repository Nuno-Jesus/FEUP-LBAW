DROP TABLE IF EXISTS Address CASCADE;
DROP TABLE IF EXISTS Card CASCADE;
DROP TABLE IF EXISTS Cart CASCADE;
DROP TABLE IF EXISTS CartProduct CASCADE;
DROP TABLE IF EXISTS Cooler CASCADE;
DROP TABLE IF EXISTS CPU CASCADE;
DROP TABLE IF EXISTS Customer CASCADE;
DROP TABLE IF EXISTS CustomerAddress CASCADE;
DROP TABLE IF EXISTS GPU CASCADE;
DROP TABLE IF EXISTS Motherboard CASCADE;
DROP TABLE IF EXISTS Notification CASCADE;
DROP TABLE IF EXISTS Other CASCADE;
DROP TABLE IF EXISTS PaymentMethod CASCADE;
DROP TABLE IF EXISTS Paypal CASCADE;
DROP TABLE IF EXISTS PcCase CASCADE;
DROP TABLE IF EXISTS PowerSupply CASCADE;
DROP TABLE IF EXISTS Product CASCADE;
DROP TABLE IF EXISTS Purchase CASCADE;
DROP TABLE IF EXISTS Review CASCADE;
DROP TABLE IF EXISTS Storage CASCADE;
DROP TABLE IF EXISTS Transfer CASCADE;
DROP TABLE IF EXISTS Wishlist CASCADE;
DROP TABLE IF EXISTS "User" CASCADE;

DROP TRIGGER IF EXISTS updateProductRating on Review;
DROP TRIGGER IF EXISTS verificaStock on CartProduct;
DROP TRIGGER IF EXISTS blockBannedUsers on Review;
DROP TRIGGER IF EXISTS orderStatusNotification on Purchase;
DROP TRIGGER IF EXISTS productSearchUpdate on Product;
DROP TRIGGER IF EXISTS renewCart on Purchase;
DROP TRIGGER IF EXISTS cartCustomer on Customer;
DROP TRIGGER IF EXISTS paymentApproved on Purchase;
DROP TRIGGER IF EXISTS priceChangeWishlist on Product;
DROP TRIGGER IF EXISTS priceChangeCart on Product;
DROP TRIGGER IF EXISTS productBackInStock on Product;
DROP TRIGGER IF EXISTS newPurchase on Purchase;

DROP FUNCTION IF EXISTS updateProductRating;
DROP FUNCTION IF EXISTS verificaStock;
DROP FUNCTION IF EXISTS blockBannedUsers;
DROP FUNCTION IF EXISTS orderStatusNotification;
DROP FUNCTION IF EXISTS productSearchUpdate;
DROP FUNCTION IF EXISTS renewCart;
DROP FUNCTION IF EXISTS cartCustomer;
DROP FUNCTION IF EXISTS paymentApproved;
DROP FUNCTION IF EXISTS priceChangeWishlist;
DROP FUNCTION IF EXISTS priceChangeCart;
DROP FUNCTION IF EXISTS productBackInStock;
DROP FUNCTION IF EXISTS newPurchase;


DROP INDEX IF EXISTS acc_id;
DROP INDEX IF EXISTS product_price;
DROP INDEX IF EXISTS product_brand;
DROP INDEX IF EXISTS cooler_type;
DROP INDEX IF EXISTS powersupply_type;
DROP INDEX IF EXISTS case_type;
DROP INDEX IF EXISTS storage_type;
DROP INDEX IF EXISTS mb_type;
DROP INDEX IF EXISTS review_author;
DROP INDEX IF EXISTS review_product;
DROP INDEX IF EXISTS review_rating;
DROP INDEX IF EXISTS order_user;
DROP INDEX IF EXISTS search_idx;

DROP TYPE IF EXISTS MotherboardType;
DROP TYPE IF EXISTS StorageType;
DROP TYPE IF EXISTS CoolerType;
DROP TYPE IF EXISTS PowerSupplyType;
DROP TYPE IF EXISTS OrderStatusType;
DROP TYPE IF EXISTS CategoryType;
DROP TYPE IF EXISTS PaymentType;

-----------------------
--       TYPES       --
-----------------------
CREATE TYPE MotherboardType as ENUM('ATX', 'MICRO-ATX', 'MINI-ATX', 'EATX', 'MINI-ITX');
CREATE TYPE StorageType as ENUM('RAM', 'SSD', 'HDD', 'M.2');
CREATE TYPE CoolerType as ENUM('Water', 'Air');
CREATE TYPE PowerSupplyType as ENUM('Full-Modular', 'Semi-Modular', 'Non-Modular');
CREATE TYPE OrderStatusType as ENUM ('Processing', 'Accepted', 'Packed', 'Shipped', 'Delivered', 'Cancelled by Store', 'Cancelled by Customer');
CREATE TYPE CategoryType as ENUM('CPU', 'GPU', 'Motherboard', 'PcCase', 'PowerSupply', 'Cooler', 'Storage', 'Other');
CREATE TYPE PaymentType as ENUM('Transfer', 'Card', 'Paypal');



-----------------------
--      TABLES       --
-----------------------
CREATE TABLE "User"(
    id SERIAL,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL check (LENGTH(password) > 8),
    email TEXT NOT NULL UNIQUE,
    phone INTEGER,
    profilePic TEXT DEFAULT 'images/default.jpg',
    isBanned BOOLEAN DEFAULT false,
    isAdmin BOOLEAN DEFAULT false,
    remember_token VARCHAR,
    
    CONSTRAINT User_PK PRIMARY KEY (id)
);

CREATE TABLE Customer(
    id SERIAL,
    id_User INTEGER,
    
    CONSTRAINT Customer_PK PRIMARY KEY(id),
    CONSTRAINT Customer_FK FOREIGN KEY(id_User) REFERENCES "User"(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Cart(
    id SERIAL,
    isActive BOOLEAN NOT NULL DEFAULT true,
    id_Customer INTEGER NOT NULL,

    CONSTRAINT Cart_PK PRIMARY KEY(id),
    CONSTRAINT Cart_FK FOREIGN KEY(id_Customer) REFERENCES Customer ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Product(
    id SERIAL,
    name TEXT NOT NULL UNIQUE,
    category CategoryType NOT NULL,
    price FLOAT NOT NULL CHECK(price > 0.0),
    size TEXT,
    stock INTEGER NOT NULL CHECK(stock >= 0),
    brand TEXT NOT NULL,
    image TEXT NOT NULL,
    rating FLOAT NOT NULL DEFAULT 0.0 CHECK(rating >= 0.0 AND rating <= 5.0),
    description TEXT NOT NULL,
    
    CONSTRAINT Product_PK PRIMARY KEY(id)
);

CREATE TABLE CPU(
    id SERIAL,
    baseFreq FLOAT NOT NULL,
    turboFreq FLOAT NOT NULL,
    socket TEXT NOT NULL,
    threads INTEGER NOT NULL,
    cores INTEGER NOT NULL,
    id_Product INTEGER NOT NULL,

    CONSTRAINT CPU_PK PRIMARY KEY(id),
    CONSTRAINT CPU_FK FOREIGN KEY(id_Product) REFERENCES Product(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE GPU(
    id SERIAL,
    memory INTEGER NOT NULL,
    coreClock INTEGER NOT NULL,
    boostClock INTEGER NOT NULL,
    hdmiPorts INTEGER NOT NULL,
    displayPorts INTEGER NOT NULL,
    id_Product INTEGER NOT NULL,

    CONSTRAINT GPU_PK PRIMARY KEY(id),
    CONSTRAINT GPU_FK FOREIGN KEY(id_Product) REFERENCES Product(id) ON DELETE CASCADE ON UPDATE CASCADE
); 

CREATE TABLE Motherboard(
    id SERIAL,
    chipset TEXT NOT NULL,
    type MotherboardType NOT NULL,
    id_Product INTEGER NOT NULL,

    CONSTRAINT Motherboard_PK PRIMARY KEY(id),
    CONSTRAINT Motherboard_FK FOREIGN KEY(id_Product) REFERENCES Product(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Storage(
    id SERIAL,
    capacity INTEGER NOT NULL,
    type StorageType NOT NULL, 
    id_Product INTEGER NOT NULL,
    
    CONSTRAINT Storage_PK PRIMARY KEY(id),
    CONSTRAINT Storage_FK FOREIGN KEY(id_Product) REFERENCES Product(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE PcCase(
    id SERIAL,
    color TEXT NOT NULL,
    weight TEXT NOT NULL,
    type MotherboardType NOT NULL,
    id_Product INTEGER NOT NULL,
    
    CONSTRAINT PcCase_PK PRIMARY KEY(id),
    CONSTRAINT PcCase_FK FOREIGN KEY(id_Product) REFERENCES Product(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Cooler(
    id SERIAL,
    type CoolerType NOT NULL,
    id_Product INTEGER NOT NULL,
    
    CONSTRAINT Cooler_PK PRIMARY KEY(id),
    CONSTRAINT Cooler_FK FOREIGN KEY(id_Product) REFERENCES Product(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE PowerSupply(
    id SERIAL,
    wattage INTEGER NOT NULL,
    type PowerSupplyType NOT NULL,
    certification TEXT NOT NULL,
    id_Product INTEGER NOT NULL,

    CONSTRAINT Powersupply_PK PRIMARY KEY(id),
    CONSTRAINT Powersupply_FK FOREIGN KEY(id_Product) REFERENCES Product(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Other(
    id SERIAL,
    id_Product INTEGER NOT NULL,

    CONSTRAINT Other_PK PRIMARY KEY(id),
    CONSTRAINT Other_FK FOREIGN KEY(id_Product) REFERENCES Product(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Review(
    id SERIAL,
    text TEXT NOT NULL CHECK (LENGTH(text) < 300),
    rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    yesVotes INTEGER NOT NULL DEFAULT 0,
    noVotes INTEGER NOT NULL DEFAULT 0,
    id_Customer INTEGER,
    id_Product INTEGER NOT NULL,

    CONSTRAINT Review_PK PRIMARY KEY(id),
    CONSTRAINT Review_FK1 FOREIGN KEY(id_Customer) REFERENCES Customer(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT Review_FK2 FOREIGN KEY(id_Product) REFERENCES Product(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Address(
    id SERIAL,
    streetName TEXT NOT NULL,
    streetNumber INTEGER NOT NULL,
    zipcode TEXT NOT NULL,
    floor INTEGER,
    aptNumber TEXT,

    CONSTRAINT Address_PK PRIMARY KEY(id)
);

CREATE TABLE PaymentMethod(
    id SERIAL NOT NULL,
    type PaymentType NOT NULL,
    CONSTRAINT PaymentMethod_PK PRIMARY KEY(id)
);

CREATE TABLE Purchase(
    id SERIAL,
    orderDate DATE NOT NULL,
    deliveryDate DATE NOT NULL,
    orderStatus OrderStatusType NOT NULL DEFAULT 'Processing',
    id_Customer INTEGER NOT NULL,
    id_Address INTEGER NOT NULL,
    id_PaymentMethod INTEGER NOT NULL,
    id_Cart INTEGER NOT NULL,

    CHECK (deliveryDate > orderDate),

    CONSTRAINT Order_PK PRIMARY KEY(id),
    CONSTRAINT Order_FK1 FOREIGN KEY(id_Customer) REFERENCES Customer(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT Order_FK2 FOREIGN KEY(id_Address) REFERENCES Address(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT Order_FK3 FOREIGN KEY(id_PaymentMethod) REFERENCES PaymentMethod(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT Order_FK4 FOREIGN KEY(id_Cart) REFERENCES Cart(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Paypal(
    id SERIAL,
    email TEXT NOT NULL UNIQUE,
    id_PaymentMethod INTEGER NOT NULL,

    CONSTRAINT Paypal_PK PRIMARY KEY(id),
    CONSTRAINT Paypal_FK FOREIGN KEY(id_PaymentMethod) REFERENCES PaymentMethod(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Card(
    id SERIAL,
    name TEXT NOT NULL,
    number BIGINT NOT NULL UNIQUE,
    expDate DATE NOT NULL CHECK (expDate > CURRENT_DATE),
    cvv INTEGER NOT NULL CHECK (cvv >= 100 AND cvv <= 999),
    id_PaymentMethod INTEGER NOT NULL,

    CONSTRAINT Card_PK PRIMARY KEY(id),
    CONSTRAINT Card_FK FOREIGN KEY(id_PaymentMethod) REFERENCES PaymentMethod(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Transfer(
    id SERIAL,
    entity INTEGER NOT NULL,
    reference BIGINT NOT NULL UNIQUE,
    validFor INTEGER NOT NULL DEFAULT 24,
    id_PaymentMethod INTEGER NOT NULL,

    CONSTRAINT Transfer_PK PRIMARY KEY(id),
    CONSTRAINT Transfer_FK FOREIGN KEY(id_PaymentMethod) REFERENCES PaymentMethod(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Notification(
    id SERIAL,
    content TEXT NOT NULL,
    id_User INTEGER NOT NULL,

    CONSTRAINT Notification_PK PRIMARY KEY(id),
    CONSTRAINT Notification_FK FOREIGN KEY(id_User) REFERENCES "User"(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE CustomerAddress(
    id_Customer INTEGER,
    id_Address INTEGER,

    CONSTRAINT CustomerAddress_PK PRIMARY KEY (id_Customer, id_Address),
    CONSTRAINT CustomerAddress_FK1 FOREIGN KEY(id_Customer) REFERENCES Customer(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT CustomerAddress_FK2 FOREIGN KEY(id_Address) REFERENCES Address(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Wishlist(
    id_Customer INTEGER,
    id_Product INTEGER,
    
    CONSTRAINT Wishlist_PK PRIMARY KEY (id_Customer, id_Product),
    CONSTRAINT Wishlist_FK1 FOREIGN KEY(id_Customer) REFERENCES Customer(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT Wishlist_FK2 FOREIGN KEY(id_Product) REFERENCES Product(id) ON DELETE CASCADE ON UPDATE CASCADE
);

 
CREATE TABLE CartProduct(
    id_Cart INTEGER,
    id_Product INTEGER,
    quantity INTEGER NOT NULL,

    CONSTRAINT CartProduct_PK PRIMARY KEY (id_Cart, id_Product),
    CONSTRAINT CartProduct_FK1 FOREIGN KEY(id_Cart) REFERENCES Cart(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT CartProduct_FK2 FOREIGN KEY(id_Product) REFERENCES Product(id) ON DELETE CASCADE ON UPDATE CASCADE
);


---------------------
--  POPULATING DB  --
---------------------

------------------------------------------------------- USER ------------------------------------------------------
INSERT INTO "User" (username, password, email) VALUES ('firstUser', '$2a$12$S23eyGrEUB1Bm/f0ZmRKfem0cHX2aLBV5Ba.1NA/FH5m2g4QNPV8C', 'vova10000@bukan.es');
INSERT INTO "User" (username, password, email) VALUES ('PaxBrandom', '$2a$12$VEdTeuHyJnJzzF4psvUHl./4/W3FEEi3mFOC63f8S5D9X6q3VJceC' ,'paxa949494@barretodrums.com');
INSERT INTO "User" (username, password, email) VALUES ('MarkPeterson', '$2a$12$A82n361fUb6LDK5lNjWC9eFexeR3GSS2sFym3awdAYHkyGZdByxai', 'iammarkp@uioct.com');
INSERT INTO "User" (username, password, email) VALUES ('LenaMaxwell' , '$2a$12$pEtkJSWrV07jGqUX0GFFfeFtVFZiFaZWgDnh1fL0vzhuxKLiYBgIe', 'lenamax@uioct.com');
INSERT INTO "User" (username, password, email) VALUES ('EvelinWatson', '$2a$12$.uRqgjKxw95SbgHDI3cYO.ySuUkWZ.4WDYAGOvv8WPJmBQX5bmKZu', 'evelinWatson@pickuplanet.com');
INSERT INTO "User" (username, password, email) VALUES ('JaggerSmith', '$2a$12$vAoUZ6jWSFUk0kC7BKLxN.84CSRGrv1VHH5i5H7drn0xJT.EVFtZi', 'jaggerSmith@pickuplanet.com');
INSERT INTO "User" (username, password, email) VALUES ('GageHubbard', '$2a$12$MLoqdeRqOFtMfkEbQTwRX.pczBEw9sDkQJXLYqF4wK3HW1Nq4b9FW', 'GageHubbard@pesssink.com');
INSERT INTO "User" (username, password, email) VALUES ('MaeveSchwartz', '$2a$12$8O4CiAb63K5kXmGJaiR.UOIL36DktkDrzQWIQPFL4YxbfDhpO6qom', 'Maeve Schwartz@shirtsthatshouldexist.com');
INSERT INTO "User" (username, password, email) VALUES ('AlexiaDickerson', '$2a$12$/I0xSWek3ek0zTy5aZNiq.zFcmoATETrPuZQQcMdmDJAeIWnHm5aq', 'alexiaDickerson@oanghika.com');
INSERT INTO "User" (username, password, email) VALUES ('ArmaniFischer', '$2a$12$GGzV4RaszJseKe7CsQZDqeSmY3TxDS6xz9Lm3x4FD.4Hz7A9Cmt1G', 'armaniFischer54@furnitt.com');
INSERT INTO "User" (username, password, email) VALUES ('FrankCurry', '$2a$12$OUraKC042t5gpoJANkvxYuMdU7jcoGV949Q/KSqe29mooeFO6SAru', 'frankCurry99@furnitt.com');
INSERT INTO "User" (username, password, email) VALUES ('AmareBurnett', '$2a$12$qBKAwvWdqyPK.CVY3/zKKOPaNCeEcZRtybKnddbBvrkGCOcTo8PL.', 'amareBurnett76@pesssink.com');
INSERT INTO "User" (username, password, email) VALUES ('JakaylaMercer', '$2a$12$Ujjp0P6IVQKPZNmAXOysvuXMsDkTcHntuK9ylkXmOvtCakafqUDcS', 'JakaylaMercer12@oanghika.com');
INSERT INTO "User" (username, password, email) VALUES ('MarvinKeller', '$2a$12$QZQPvDdXIoE7UgRP5VyFtOxsMVXEXxr1cS2p.7oQueY816CtFcFbK', 'MarvinKeller55@furnitt.com');
INSERT INTO "User" (username, password, email) VALUES ('DestinyJacobs', '$2a$12$g1mKNdb90Cps.hXZZVrNOuiVZqkaWl/ED.BryvLIcU80DPgxMAgbO', 'DestinyJacobs2@shirtsthatshouldexist.com');
INSERT INTO "User" (username, password, email) VALUES ('QuintenCrosby', '$2a$12$RGJ2gizpMBNqvJuXIhDIWeqbry7j0hZ6I4H8Fa4EQa5mzdDOWpy/a', 'QuintenCrosby22@pesssink.com');
INSERT INTO "User" (username, password, email) VALUES ('DarnellDrake', '$2a$12$cPlSlwJZRoGdnqb36HrIy./q3ZUtyieEJ0Bf3qzHFi3fOgUdJ0dfK', 'DarnellDrake69@oanghika.com');
INSERT INTO "User" (username, password, email) VALUES ('WillLarson', '$2a$12$hPeLerI3W6ltR3ecuETYYODB740XnYmOzaiXVj8s1pJ8yrB65VeBe', 'WillLarson23@shirtsthatshouldexist.com');
INSERT INTO "User" (username, password, email) VALUES ('OdinHarrell', '$2a$12$XADtifoRpl7Cw/Ju392QoOEsaED5oJD5UcvqRqjVuJVLQBBG1iTS.', 'OdinHarrel1992@shirtsthatshouldexist.com');
INSERT INTO "User" (username, password, email) VALUES ('RihannaRosales', '$2a$12$fNm.hHEx81mG.S.NT6A9oeaZPWWRkjnvJPgxw2D5vg0mymY5qcrUG', 'RihannaRosales1990@pesssink.com');
INSERT INTO "User" (username, password, email) VALUES ('GideonRuiz', '$2a$12$8YJKRaqD4PDVb17IYc9Jou3t0RtTND/p8Fwuu3FrDp94rQvrBOPCm', 'GideonRuiz2002@shirtsthatshouldexist.com');
INSERT INTO "User" (username, password, email) VALUES ('GianniPotts', '$2a$12$7lutxXb3yaMyK4UVw5qJs.1y8IE3cBEsx697VZHwZBW6m7ELV2w4y', 'GianniPotts42@oanghika.com');
INSERT INTO "User" (username, password, email) VALUES ('NikoValencia', '$2a$12$Fywos/9DM/WId1vOnVnHxO8Jkvza7DEbqk9SNMzNO/O0eHuNxuLc.', 'NikoValencia97@oanghika.es');
INSERT INTO "User" (username, password, email) VALUES ('GaryOdom', '$2a$12$VLg/g4F..eDdnFNxdznfvuvAu4iRdUvHAxG7AzPqs4qokINBNB44e', 'GaryOdom33@furnitt.com');
INSERT INTO "User" (username, password, email) VALUES ('CamrenSpears', '$2a$12$D96dLBEmF2A5tJkwei7eBOfx.8xpQ0Gyu4FRV.CnOXcu5RTVB5mTq', 'CamrenSpears88@furnitt.com');
INSERT INTO "User" (username, password, email) VALUES ('MohamedKaiser', '$2a$12$qBzFtr0L9jxm/rPUxO2Uqe.0d41qjzIuNAW2I7X.OUnALqS4oj75C', 'MohamedKaiser123@pesssink.com');
INSERT INTO "User" (username, password, email, isBanned) VALUES ('BannedUser', '$2a$12$rpiLNuZzwTrTfIQ9Xmze.u4BHyL34d4Vr65jH8ZsL.dZcovmskYUm', 'Banneduser@pesssink.com', true);
INSERT INTO "User" (username, password, email, isAdmin) VALUES ('up201907716', '$2a$12$2G4raEwids5Bo4DWb4OoMuekza2vBLycBntZkZgfg2e4ZMUflkQkq', 'up201907716@up.pt', true);
INSERT INTO "User" (username, password, email, isAdmin) VALUES ('up201905497', '$2a$12$2G4raEwids5Bo4DWb4OoMuekza2vBLycBntZkZgfg2e4ZMUflkQkq', 'up201905497@up.pt', true);
INSERT INTO "User" (username, password, email, isAdmin) VALUES ('up201905477', '$2a$12$2G4raEwids5Bo4DWb4OoMuekza2vBLycBntZkZgfg2e4ZMUflkQkq', 'up201905477@up.pt', true);
INSERT INTO "User" (username, password, email, isAdmin) VALUES ('up201800700', '$2a$12$2G4raEwids5Bo4DWb4OoMuekza2vBLycBntZkZgfg2e4ZMUflkQkq', 'up201800700@up.pt', true);
    
    
------------------ Customer --------------------
INSERT INTO Customer (id_User) VALUES (1);
INSERT INTO Customer (id_User) VALUES (2);
INSERT INTO Customer (id_User) VALUES (3);
INSERT INTO Customer (id_User) VALUES (4);
INSERT INTO Customer (id_User) VALUES (5);
INSERT INTO Customer (id_User) VALUES (6);
INSERT INTO Customer (id_User) VALUES (7);
INSERT INTO Customer (id_User) VALUES (8);
INSERT INTO Customer (id_User) VALUES (9);
INSERT INTO Customer (id_User) VALUES (10);
INSERT INTO Customer (id_User) VALUES (11);
INSERT INTO Customer (id_User) VALUES (12);
INSERT INTO Customer (id_User) VALUES (13);
INSERT INTO Customer (id_User) VALUES (14);
INSERT INTO Customer (id_User) VALUES (15);
INSERT INTO Customer (id_User) VALUES (16);
INSERT INTO Customer (id_User) VALUES (17);
INSERT INTO Customer (id_User) VALUES (18);
INSERT INTO Customer (id_User) VALUES (19);
INSERT INTO Customer (id_User) VALUES (20);
INSERT INTO Customer (id_User) VALUES (21);
INSERT INTO Customer (id_User) VALUES (22);
INSERT INTO Customer (id_User) VALUES (23);
INSERT INTO Customer (id_User) VALUES (24);
INSERT INTO Customer (id_User) VALUES (25);
INSERT INTO Customer (id_User) VALUES (26);
INSERT INTO Customer (id_User) VALUES (27);

-------------- Cart ---------------
INSERT INTO Cart (id_Customer) VALUES (1);
INSERT INTO Cart (id_Customer) VALUES (2);
INSERT INTO Cart (id_Customer) VALUES (3);
INSERT INTO Cart (id_Customer) VALUES (4);
INSERT INTO Cart (id_Customer) VALUES (5);
INSERT INTO Cart (id_Customer) VALUES (6);
INSERT INTO Cart (id_Customer) VALUES (7);
INSERT INTO Cart (id_Customer) VALUES (8);
INSERT INTO Cart (id_Customer) VALUES (9);
INSERT INTO Cart (id_Customer) VALUES (10);
INSERT INTO Cart (id_Customer) VALUES (11);
INSERT INTO Cart (id_Customer) VALUES (12);
INSERT INTO Cart (id_Customer) VALUES (13);
INSERT INTO Cart (id_Customer) VALUES (14);
INSERT INTO Cart (id_Customer) VALUES (15);
INSERT INTO Cart (id_Customer) VALUES (16);
INSERT INTO Cart (id_Customer) VALUES (17);
INSERT INTO Cart (id_Customer) VALUES (18);
INSERT INTO Cart (id_Customer) VALUES (19);
INSERT INTO Cart (id_Customer) VALUES (20);
INSERT INTO Cart (id_Customer) VALUES (21);
INSERT INTO Cart (id_Customer) VALUES (22);
INSERT INTO Cart (id_Customer) VALUES (23);
INSERT INTO Cart (id_Customer) VALUES (24);
INSERT INTO Cart (id_Customer) VALUES (25);
INSERT INTO Cart (id_Customer) VALUES (26);
INSERT INTO Cart (id_Customer) VALUES (27);

-------------------------------------------------------------------------------------------------------------- CPU ------------------------------------------------------------------------------------------------------------
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('AMD Ryzen 9 5950X', 'CPU', 836.99, 'AMD', 5, 'images/1.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('INTEL-Core i9-9900K', 'CPU', 571.99, 'Intel', 3, 'images/2.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('INTEL Core i5-10600K', 'CPU', 247.99, 'Intel', 5, 'images/3.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('AMD Ryzen 7 5800X', 'CPU', 449.99, 'AMD', 4, 'images/4.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('INTEL Celeron G5925', 'CPU', 54.99, 'Intel', 6, 'images/5.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('AMD Ryzen Threadripper 3990X', 'CPU', 4299.99, 'AMD', 3, 'images/6.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Intel Core i9-12900K', 'CPU', 665.90, 'Intel', 2, 'images/7.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('AMD Ryzen 3 3200G', 'CPU', 114.90, 'AMD', 1, 'images/8.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('INTEL-Core i5-9600K', 'CPU', 188.10, 'Intel', 0, 'images/9.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('AMD Ryzen 5 3600X', 'CPU', 245.90, 'AMD', 4, 'images/10.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');

INSERT INTO CPU(baseFreq, turboFreq, socket, threads, cores, id_Product) VALUES (3.4, 4.9, 'AM4', 32, 16, 1);
INSERT INTO CPU(baseFreq, turboFreq, socket, threads, cores, id_Product) VALUES (3.6, 5, 'LGA1151', 16, 8, 2);
INSERT INTO CPU(baseFreq, turboFreq, socket, threads, cores, id_Product) VALUES (4.1, 4.8, 'LGA1200', 12, 6, 3);
INSERT INTO CPU(baseFreq, turboFreq, socket, threads, cores, id_Product) VALUES (3.8, 4.7, 'AM4', 16, 8, 4);
INSERT INTO CPU(baseFreq, turboFreq, socket, threads, cores, id_Product) VALUES (3.6, 0, 'LGA1200', 2, 2, 5);
INSERT INTO CPU(baseFreq, turboFreq, socket, threads, cores, id_Product) VALUES (2.9, 4.3, 'TRX4', 128, 64, 6);
INSERT INTO CPU(baseFreq, turboFreq, socket, threads, cores, id_Product) VALUES (3.2, 5.2, 'LGA1700', 24, 16, 7);
INSERT INTO CPU(baseFreq, turboFreq, socket, threads, cores, id_Product) VALUES (3.6, 3.9, 'AM4', 4, 4, 8);
INSERT INTO CPU(baseFreq, turboFreq, socket, threads, cores, id_Product) VALUES (3.7, 4.6, 'LGA1151', 6, 6, 9);
INSERT INTO CPU(baseFreq, turboFreq, socket, threads, cores, id_Product) VALUES (3.8, 4.4, 'AM4', 12, 6, 10);


-------------------------------------------------------------------------------------------------------------- GPU ------------------------------------------------------------------------------------------------------------
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('ASUS GeForce GT 1030', 'GPU', 115.72, 'ASUS', 15, 'images/11.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('ASUS ROG STRIX GeForce RTX 3080 Ti', 'GPU', 1899.00, 'ASUS', 2, 'images/12.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('MSI GeForce GT 710', 'GPU', 78.16, 'MSI', 6, 'images/13.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Gigabyte Radeon RX 6700 XT', 'GPU', 929.90, 'Gigabyte', 6, 'images/14.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Gigabyte Radeon RX 6800 XT', 'GPU', 1399.90, 'Gigabyte', 3, 'images/15.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Zotac Gaming GeForce RTX 3090', 'GPU', 3049.90, 'Zotac', 1, 'images/16.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('XFX Radeon RX 6600 XT', 'GPU', 899.90, 'XFX', 2, 'images/17.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Gigabyte Radeon RX 6900 XT', 'GPU', 1469.90, 'Gigabyte', 8, 'images/18.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum'); 
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('EVGA GeForce RTX 3080 FTW', 'GPU', 1499.90, 'EVGA', 7, 'images/19.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Zotac Gaming GeForce RTX 3060 Ti', 'GPU', 1099.00, 'Zotac', 4, 'images/20.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');


INSERT INTO GPU(memory, coreClock, boostClock, hdmiPorts, displayPorts, id_Product) VALUES (2, 1252, 1531, 1, 0, 11);
INSERT INTO GPU(memory, coreClock, boostClock, hdmiPorts, displayPorts, id_Product) VALUES (12, 1365, 1845, 2, 3, 12);
INSERT INTO GPU(memory, coreClock, boostClock, hdmiPorts, displayPorts, id_Product) VALUES (2, 954, 954, 1, 0, 13);
INSERT INTO GPU(memory, coreClock, boostClock, hdmiPorts, displayPorts, id_Product) VALUES (12, 2321, 2622, 2, 2, 14);
INSERT INTO GPU(memory, coreClock, boostClock, hdmiPorts, displayPorts, id_Product) VALUES (16, 2045, 2285, 2, 2, 15);
INSERT INTO GPU(memory, coreClock, boostClock, hdmiPorts, displayPorts, id_Product) VALUES (24, 1395, 1710, 1, 3, 16);
INSERT INTO GPU(memory, coreClock, boostClock, hdmiPorts, displayPorts, id_Product) VALUES (8, 2092, 2589, 1, 3, 17);
INSERT INTO GPU(memory, coreClock, boostClock, hdmiPorts, displayPorts, id_Product) VALUES (16, 2050, 2285, 1, 2, 18);
INSERT INTO GPU(memory, coreClock, boostClock, hdmiPorts, displayPorts, id_Product) VALUES (10, 1440, 1800, 1, 3, 19);
INSERT INTO GPU(memory, coreClock, boostClock, hdmiPorts, displayPorts, id_Product) VALUES (8, 1410, 1695, 1, 3, 20);


-------------------------------------------------------------------------------------------------------------- Motherboard ------------------------------------------------------------------------------------------------------------
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('ASUS TUF Gaming X570-PLUS (Wi-fi)', 'Motherboard', 237.90, 'ASUS', 4, 'images/21.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('ASUS ROG Strix Z590-F Gaming Wi-Fi', 'Motherboard', 313.90, 'ASUS', 2, 'images/22.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('MSI MPG X570 Gaming Edge', 'Motherboard', 221.32, 'MSI', 3, 'images/23.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('MSI MPG X570 Gaming Plus', 'Motherboard', 185.29, 'MSI', 6, 'images/24.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('ASUS ROG Maximus Z690 Extreme Glacial', 'Motherboard', 1928.90, 'ASUS', 1, 'images/25.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('ASUS ROG Crosshair VIII Impact', 'Motherboard', 409.90, 'ASUS', 0, 'images/26.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Gigabyte B450 | Aorus Pro WiFi', 'Motherboard', 116.90, 'Gigabyte', 0, 'images/27.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('ASRock TRX40 Taichi', 'Motherboard', 499.90, 'ASRock', 4, 'images/28.jpg',  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Gigabyte B450 Gaming X', 'Motherboard', 75.90, 'Gigabyte', 7, 'images/29.jpg',  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('ASUS ROG Maximus XII Apex', 'Motherboard', 495.90, 'ASUS', 3, 'images/30.jpg',  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');


INSERT INTO Motherboard(chipset, type, id_Product) VALUES ('AM4 Z570', 'ATX', 21);
INSERT INTO Motherboard(chipset, type, id_Product) VALUES ('Intel Z590', 'ATX', 22);
INSERT INTO Motherboard(chipset, type, id_Product) VALUES ('AMD X570', 'ATX', 23);
INSERT INTO Motherboard(chipset, type, id_Product) VALUES ('AMD X570', 'ATX', 24);
INSERT INTO Motherboard(chipset, type, id_Product) VALUES ('Intel Z690', 'EATX', 25);
INSERT INTO Motherboard(chipset, type, id_Product) VALUES ('AM4 Z570', 'MINI-ITX', 26);
INSERT INTO Motherboard(chipset, type, id_Product) VALUES ('AMD B450', 'MINI-ITX', 27);
INSERT INTO Motherboard(chipset, type, id_Product) VALUES ('AMD TRX40', 'ATX', 28);
INSERT INTO Motherboard(chipset, type, id_Product) VALUES ('AMD B450', 'ATX', 29);
INSERT INTO Motherboard(chipset, type, id_Product) VALUES ('INTEL Z490', 'ATX', 30);


-------------------------------------------------------------------------------------------------------------- Storage ------------------------------------------------------------------------------------------------------------
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('G.SKILL Trident Z5 RGB 32GB (2x16GB) DDR5-5600MHz', 'Storage', 393.60, 'G.SKILL', 4, 'images/31.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Kingston Fury Beast 32GB (2x16GB) DDR5-5200MHz', 'Storage', 315.30, 'Kingston', 5, 'images/32.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Corsair Vengeance RGB Pro 16GB (2x8GB) DDR4-3200MHz', 'Storage', 92.70, 'Storage', 0, 'images/33.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('G.SKILL Trident Z 16GB (2x8GB) DDR4-3200MHz', 'Storage', 113.30, 'G.SKILL', 21, 'images/34.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('G.SKILL SNIPER X 16GB (2x8GB) DDR4-3000MHz', 'Storage', 73.70, 'G.SKILL', 12, 'images/35.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Samsung 970 Evo Plus', 'Storage', 139.99, 'Samsung', 31, 'images/36.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Kingston A400', 'Storage', 27.99, 'KINGSTON', 4, 'images/37.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Western Digital Blue 500GB', 'Storage', 59.90, 'Western Digital', 7, 'images/38.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('eagate IronWolf 8TB', 'Storage', 244.90, 'Seagate', 8, 'images/39.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Samsung 980 Pro', 'Storage', 79.98, 'Samsung', 8, 'images/40.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');

INSERT INTO Storage(type, capacity, id_Product) VALUES ('RAM', 32, 31);
INSERT INTO Storage(type, capacity, id_Product) VALUES ('RAM', 32, 32);
INSERT INTO Storage(type, capacity, id_Product) VALUES ('RAM', 16, 33);
INSERT INTO Storage(type, capacity, id_Product) VALUES ('RAM', 16, 34);
INSERT INTO Storage(type, capacity, id_Product) VALUES ('RAM', 16, 35);
INSERT INTO Storage(type, capacity, id_Product) VALUES ('M.2', 1000, 36);
INSERT INTO Storage(type, capacity, id_Product) VALUES ('SSD', 240, 37);
INSERT INTO Storage(type, capacity, id_Product) VALUES ('HDD', 500, 38);
INSERT INTO Storage(type, capacity, id_Product) VALUES ('HDD', 8000, 39);
INSERT INTO Storage(type, capacity, id_Product) VALUES ('M.2', 250, 40);


-------------------------------------------------------------------------------------------------------------- PcCase ------------------------------------------------------------------------------------------------------------
INSERT INTO Product(name, category, price, brand, size, stock, image, description) VALUES ('Corsair Super-Tower Obsidian 1000D', 'PcCase', 589.90, 'CORSAIR', '697mm x 307mm x 693mm', 7, 'images/41.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, size, stock, image, description) VALUES ('Antec Torque', 'PcCase', 389.90, 'Antec', '621mm x 285mm x 644 mm', 0, 'images/42.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, size, stock, image, description) VALUES ('Asus ROG Strix Helios', 'PcCase', 305.90, 'Asus', '591mm x 250mm x 565 mm', 2, 'images/43.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, size, stock, image, description) VALUES ('Cooler Master MasterBox NR200P', 'PcCase', 135.98, 'Cooler Master', '376mm x 185mm x 292mm', 21, 'images/44.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, size, stock, image, description) VALUES ('Corsair Crystal 680X RGB', 'PcCase', 254.90, 'Corsair', '732mm x 280mm x 688mm', 3, 'images/45.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, size, stock, image, description) VALUES ('Fractal Design Era ITX Gold', 'PcCase', 149.90, 'Fractal Design', '325mm x 166mm x 310 mm', 4, 'images/46.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, size, stock, image, description) VALUES ('Kolink Rocket V2', 'PcCase', 149.90, 'Kolink', '350mm x 150mm x 270mm', 2, 'images/47.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, size, stock, image, description) VALUES ('Corsair iCUE 4000X RGB', 'PcCase', 114.89, 'CORSAIR', '453mm x 230mm x 466mm', 12, 'images/48.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, size, stock, image, description) VALUES ('NZXT H510', 'PcCase', 79.90, 'NZXT', '428mm x 210mm x 460mm', 6, 'images/49.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, size, stock, image, description) VALUES ('NZXT H710', 'PcCase', 149.90,'NZXT', '494mm x 230mm x 516mm', 7, 'images/50.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');

INSERT INTO PcCase(type, weight, color, id_Product) VALUES ('EATX', 29.5, 'Black', 41);
INSERT INTO PcCase(type, weight, color, id_Product) VALUES ('EATX', 9.4, 'Black/Red', 42);
INSERT INTO PcCase(type, weight, color, id_Product) VALUES ('EATX', 18, 'Black', 43);
INSERT INTO PcCase(type, weight, color, id_Product) VALUES ('MINI-ITX', 5, 'Black', 44);
INSERT INTO PcCase(type, weight, color, id_Product) VALUES ('EATX', 11.6, 'White', 45);
INSERT INTO PcCase(type, weight, color, id_Product) VALUES ('MINI-ITX', 3, 'Gold', 46);
INSERT INTO PcCase(type, weight, color, id_Product) VALUES ('MINI-ITX', 2.6, 'Gunmetal', 47);
INSERT INTO PcCase(type, weight, color, id_Product) VALUES ('ATX', 7.85, 'Black', 48);
INSERT INTO PcCase(type, weight, color, id_Product) VALUES ('ATX', 6.6, 'White', 49);
INSERT INTO PcCase(type, weight, color, id_Product) VALUES ('EATX', 12.1, 'White', 50);

-------------------------------------------------------------------------------------------------------------- Cooler ------------------------------------------------------------------------------------------------------------
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Noctua NH-D15', 'Cooler', 109.90, 'Noctua', 8, 'images/51.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, size, stock, image, description) VALUES ('Corsair iCUE H100i RGB Elite Capellix', 'Cooler', 154.90, 'Corsair', '240mm', 4, 'images/52.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Cooler Master Wraith Ripper', 'Cooler', 119.90, 'Cooler Master', 12,  'images/53.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Arctic Freezer 50 TR', 'Cooler', 74.90, 'Arctic', 2, 'images/54.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('AMD Wraith Prism', 'Cooler', 54.90, 'AMD', 2, 'images/55.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Arctic Freezer 34 eSports DUO', 'Cooler', 44.90, 'Arctic', 3, 'images/56.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Noctua NH-D15 Chromax Black', 'Cooler', 114.90, 'Noctua' , 7, 'images/57.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, size, stock, image, description) VALUES ('Cooler CPU Corsair iCUE H150i Elite LCD Display RGB', 'Cooler', 293.90, 'Corsair', '360mm', 3, 'images/58.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, size, stock, image, description) VALUES ('Corsair iCUE H150i RGB Elite Capellix', 'Cooler', 184.90, 'Corsair', '360mm', 5,'images/59.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, size, stock, image, description) VALUES ('Mars Gaming ML120 RGB', 'Cooler', 54.90, 'Mars Gaming', '120mm', 12,'images/60.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');


INSERT INTO Cooler(type, id_Product) VALUES ('Air', 51);
INSERT INTO Cooler(type, id_Product) VALUES ('Water', 52);
INSERT INTO Cooler(type, id_Product) VALUES ('Air', 53);
INSERT INTO Cooler(type, id_Product) VALUES ('Air', 54);
INSERT INTO Cooler(type, id_Product) VALUES ('Air', 55);
INSERT INTO Cooler(type, id_Product) VALUES ('Air', 56);
INSERT INTO Cooler(type, id_Product) VALUES ('Air', 57);
INSERT INTO Cooler(type, id_Product) VALUES ('Water', 58);
INSERT INTO Cooler(type, id_Product) VALUES ('Water', 59);
INSERT INTO Cooler(type, id_Product) VALUES ('Water', 60);


-------------------------------------------------------------------------------------------------------------- PowerSupply ------------------------------------------------------------------------------------------------------------
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Corsair RM (2019)', 'PowerSupply', 112.90, 'Corsair', 5, 'images/61.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('EVGA BQ', 'PowerSupply', 64.90, 'EVGA',  3, 'images/62.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Nox Urano TX', 'PowerSupply', 64.50, 'Nox', 1, 'images/63.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Corsair RMx Series RM850x', 'PowerSupply', 139.90, 'Corsair', 4, 'images/64.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('MSI MPG A-GF', 'PowerSupply', 124.90, 'MSI', 2, 'images/65.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Gigabyte Aorus P1200', 'PowerSupply', 349.90, 'Gigabyte', 3, 'images/66.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Asus ROG Strix 850W', 'PowerSupply', 204.90, 'Asus', 1, 'images/67.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Seasonic Core GC', 'PowerSupply', 79.90, 'Seasonic', 3, 'images/68.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Nox Urano VX', 'PowerSupply', 60.90, 'Nox', 7, 'images/69.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('EVGA SuperNOVA GT', 'PowerSupply', 98.90, 'EVGA', 6, 'images/70.jpg' , 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');

INSERT INTO PowerSupply(wattage, certification, type, id_Product) VALUES (750, '80+ Gold', 'Full-Modular', 61);
INSERT INTO PowerSupply(wattage, certification, type, id_Product) VALUES (500, '80+ Bronze', 'Semi-Modular', 62);
INSERT INTO PowerSupply(wattage, certification, type, id_Product) VALUES (850, '80+ Bronze', 'Full-Modular', 63);
INSERT INTO PowerSupply(wattage, certification, type, id_Product) VALUES (850, '80+ Gold', 'Full-Modular', 64);
INSERT INTO PowerSupply(wattage, certification, type, id_Product) VALUES (650, '80+ Gold', 'Full-Modular', 65);
INSERT INTO PowerSupply(wattage, certification, type, id_Product) VALUES (1200, '80+ Platinum', 'Full-Modular', 66);
INSERT INTO PowerSupply(wattage, certification, type, id_Product) VALUES (850, '80+ Gold', 'Full-Modular', 67);
INSERT INTO PowerSupply(wattage, certification, type, id_Product) VALUES (650, '80+ Gold', 'Full-Modular', 68);
INSERT INTO PowerSupply(wattage, certification, type, id_Product) VALUES (750, '80+ Bronze', 'Full-Modular', 69);
INSERT INTO PowerSupply(wattage, certification, type, id_Product) VALUES (650, '80+ Gold', 'Full-Modular', 70);

------------------------------------------------------------------------------------------------------------------ Other ---------------------------------------------------------------------------------------------------------------------------
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Corsair Lighting Node Pro RGB', 'Other', 52.90, 'Corsair', 4, 'images/71.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Thermal Grizzly Kryonaut 11.1g', 'Other', 24.90, 'Thermal Grizzly', 35, 'images/72.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Razer PWM PC Fan Controller', 'Other', 54.90, 'Razer', 6, 'images/73.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Asus Blu-Ray Reader', 'Other', 93.90, 'Asus', 1, 'images/74.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Asus ROG Xonar Phoebus Soundcard', 'Other', 154.90, 'Asus', 2, 'images/75.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('Asus USB-AC53 Nano Wi-Fi Adapter', 'Other', 29.90, 'Asus', 26, 'images/76.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('TP-Link UB500 Bluetooth Adapter', 'Other', 11.90, 'TP-Link', 21, 'images/77.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');
INSERT INTO Product(name, category, price, brand, stock, image, description) VALUES ('TP-Link UH700 7-Port USB HUB', 'Other', 28.90, 'TP-Link', 0,'images/78.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tellus risus, ornare vel faucibus ut, tempor at ipsum. Nam elementum');


INSERT INTO OTHER (id_Product) VALUES (71);
INSERT INTO OTHER (id_Product) VALUES (72);
INSERT INTO OTHER (id_Product) VALUES (73);
INSERT INTO OTHER (id_Product) VALUES (74);
INSERT INTO OTHER (id_Product) VALUES (75);
INSERT INTO OTHER (id_Product) VALUES (76);
INSERT INTO OTHER (id_Product) VALUES (77);
INSERT INTO OTHER (id_Product) VALUES (78);

-------------------------------------------------------------------------------------------------------------- Address ------------------------------------------------------------------------------------------------------------
INSERT INTO Address(streetName, streetNumber, zipcode) VALUES ('Rua Costa' , 108, '4795-158 Santo Tirso');
INSERT INTO Address(streetName, streetNumber, zipcode) VALUES ('Rua Nossa Senhora Fátima', 95, '3330-350 Góis');
INSERT INTO Address(streetName, streetNumber, zipcode, floor, aptNumber) VALUES ('Rua Capitão Henrique Galvão', 94, '2705-210 Sintra', 3, 'Esquerdo');
INSERT INTO Address(streetName, streetNumber, zipcode, floor, aptNumber) VALUES ('Rua Caminho Cruz' , 102, '4450-540 Matosinhos', 7, 'Trás');
INSERT INTO Address(streetName, streetNumber, zipcode) VALUES ('Rua Germana Tânger' , 123, '2725-239 Mem-Martins');
INSERT INTO Address(streetName, streetNumber, zipcode) VALUES ('Rua Cruzes', 72, '4750-791 Esposende');
INSERT INTO Address(streetName, streetNumber, zipcode) VALUES ('Rua Poeta João Ruiz', 151, '6225-259 Covilhã');
INSERT INTO Address(streetName, streetNumber, zipcode) VALUES ('Rua Parque Gondarim', 82, '4405-747 Vila Nova de Gaia');
INSERT INTO Address(streetName, streetNumber, zipcode, floor, aptNumber) VALUES ('Praceta Conde Arnoso', 102, '2640-097 Mafra', 2, 'Frente');
INSERT INTO Address(streetName, streetNumber, zipcode, floor, aptNumber) VALUES ('Rua Regato ', 83, '6090-025 Penamacor', 0, 'Direito');
INSERT INTO Address(streetName, streetNumber, zipcode) VALUES ('Rua Vale Formoso', 18, '8000-426 Faro');
INSERT INTO Address(streetName, streetNumber, zipcode, floor, aptNumber) VALUES ('Rua Projetada', 108, '2900-593 Setúbal', 6, 'Trás');
INSERT INTO Address(streetName, streetNumber, zipcode, floor, aptNumber) VALUES ('Rua São Gonçalo', 1052, '7830-374 Serpa', 1, 'Esquerdo');
INSERT INTO Address(streetName, streetNumber, zipcode) VALUES ('Avenida Boavista', 108, '4920-100 Vila Nova de Cerveira');
INSERT INTO Address(streetName, streetNumber, zipcode) VALUES ('Rua Cabral Antunes', 69, '3750-721 Águeda');
INSERT INTO Address(streetName, streetNumber, zipcode) VALUES ('Rua da Estrada', 108, '6512-100 Beja');
INSERT INTO Address(streetName, streetNumber, zipcode) VALUES ('Rua Heróis Ultramar', 56, '2205-623 Abrantes');
INSERT INTO Address(streetName, streetNumber, zipcode, floor, aptNumber) VALUES ('Rua Doutor Alfredo Freitas', 128, '3700-383 Santa Maria da Feira', 3, 'Frente');
INSERT INTO Address(streetName, streetNumber, zipcode) VALUES ('Rua Rainha Santa Isabel', 1044, '3250-265 Alvaiázere');
INSERT INTO Address(streetName, streetNumber, zipcode, floor, aptNumber) VALUES ('Rua Cimo Vila', 125, '4590-083 Paços de Ferreira', 1, 'Esquerdo');

------------- PaymentMethod -------------
INSERT INTO PaymentMethod(type) VALUES ('Paypal');
INSERT INTO PaymentMethod(type) VALUES ('Paypal');
INSERT INTO PaymentMethod(type) VALUES ('Paypal');
INSERT INTO PaymentMethod(type) VALUES ('Paypal');
INSERT INTO PaymentMethod(type) VALUES ('Paypal');
INSERT INTO PaymentMethod(type) VALUES ('Paypal');
INSERT INTO PaymentMethod(type) VALUES ('Card');
INSERT INTO PaymentMethod(type) VALUES ('Card');
INSERT INTO PaymentMethod(type) VALUES ('Card');
INSERT INTO PaymentMethod(type) VALUES ('Card');
INSERT INTO PaymentMethod(type) VALUES ('Card');
INSERT INTO PaymentMethod(type) VALUES ('Card');
INSERT INTO PaymentMethod(type) VALUES ('Paypal');
INSERT INTO PaymentMethod(type) VALUES ('Paypal');
INSERT INTO PaymentMethod(type) VALUES ('Card');

--------------------------- Purchase ---------------------------
INSERT INTO Purchase(orderDate, deliveryDate, orderStatus, id_Customer, id_Address, id_PaymentMethod, id_Cart) VALUES ('2021/11/28', '2021/12/4', 'Processing', 1, 1, 1, 1);
INSERT INTO Purchase(orderDate, deliveryDate, orderStatus, id_Customer, id_Address, id_PaymentMethod, id_Cart) VALUES ('2021/11/16', '2021/11/19', 'Accepted', 1, 1, 1, 1);
INSERT INTO Purchase(orderDate, deliveryDate, orderStatus, id_Customer, id_Address, id_PaymentMethod, id_Cart) VALUES ('2021/11/28', '2021/12/2',  'Packed', 1, 1, 1, 1);
INSERT INTO Purchase(orderDate, deliveryDate, orderStatus, id_Customer, id_Address, id_PaymentMethod, id_Cart) VALUES ('2021/11/27', '2021/11/30', 'Shipped', 1, 1, 1, 1);
INSERT INTO Purchase(orderDate, deliveryDate, orderStatus, id_Customer, id_Address, id_PaymentMethod, id_Cart) VALUES ('2021/11/16', '2021/11/19', 'Delivered', 1, 1, 1, 1);
INSERT INTO Purchase(orderDate, deliveryDate, orderStatus, id_Customer, id_Address, id_PaymentMethod, id_Cart) VALUES ('2021/11/16', '2021/11/19', 'Cancelled by Store', 1, 1, 1, 1);
INSERT INTO Purchase(orderDate, deliveryDate, orderStatus, id_Customer, id_Address, id_PaymentMethod, id_Cart) VALUES ('2021/11/16', '2021/11/19', 'Cancelled by Customer', 1, 1, 1, 1);

--------------------------- Paypal ---------------------------
INSERT INTO Paypal (email, id_PaymentMethod) VALUES ('vova10000@bukan.es', 1);
INSERT INTO Paypal (email, id_PaymentMethod) VALUES ('paxa949494@barretodrums.com', 2);
INSERT INTO Paypal (email, id_PaymentMethod) VALUES ('iammarkp@uioct.com', 3);
INSERT INTO Paypal (email, id_PaymentMethod) VALUES ('lenamax@uioct.com', 4);
INSERT INTO Paypal (email, id_PaymentMethod) VALUES ('evelinWatson@pickuplanet.com', 5);
INSERT INTO Paypal (email, id_PaymentMethod) VALUES ('Maeve Schwartz@shirtsthatshouldexist.com', 6);
INSERT INTO Paypal (email, id_PaymentMethod) VALUES ('CamrenSpears88@furnitt.com', 13);
INSERT INTO Paypal (email, id_PaymentMethod) VALUES ('MohamedKaiser123@pesssink.com', 14);

--------------------------- Card ---------------------------
INSERT INTO Card (name, number, expDate, cvv, id_PaymentMethod) VALUES ('Marvin Keller', 5557028833413930, '2022/12/01', 752, 7);
INSERT INTO Card (name, number, expDate, cvv, id_PaymentMethod) VALUES ('Quinten Crosby', 4532809664584094, '2024/11/01', 684, 8);
INSERT INTO Card (name, number, expDate, cvv, id_PaymentMethod) VALUES ('Will Larson', 5421946123168068, '2023/12/01', 491, 9);
INSERT INTO Card (name, number, expDate, cvv, id_PaymentMethod) VALUES ('Odin Harrell', 5118045101110610, '2025/02/01', 892, 10);
INSERT INTO Card (name, number, expDate, cvv, id_PaymentMethod) VALUES ('Rihanna Rosales', 4485749073456673, '2024/01/01', 515, 11);
INSERT INTO Card (name, number, expDate, cvv, id_PaymentMethod) VALUES ('Gideon Ruiz', 4485488722909754, '2024/04/01', 539, 12);
INSERT INTO Card (name, number, expDate, cvv, id_PaymentMethod) VALUES ('George Washinghton', 5135825255955624, '2023/02/01', 451, 15);

------------------------ CustomerAddress --------------------------
INSERT INTO CustomerAddress (id_Customer, id_Address) VALUES (1, 5);
INSERT INTO CustomerAddress (id_Customer, id_Address) VALUES (2, 6);
INSERT INTO CustomerAddress (id_Customer, id_Address) VALUES (3, 7);
INSERT INTO CustomerAddress (id_Customer, id_Address) VALUES (4, 8);
INSERT INTO CustomerAddress (id_Customer, id_Address) VALUES (5, 9);
INSERT INTO CustomerAddress (id_Customer, id_Address) VALUES (6, 10);
INSERT INTO CustomerAddress (id_Customer, id_Address) VALUES (7, 11);
INSERT INTO CustomerAddress (id_Customer, id_Address) VALUES (8, 12);
INSERT INTO CustomerAddress (id_Customer, id_Address) VALUES (9, 13);
INSERT INTO CustomerAddress (id_Customer, id_Address) VALUES (10, 14);
INSERT INTO CustomerAddress (id_Customer, id_Address) VALUES (11, 15);
INSERT INTO CustomerAddress (id_Customer, id_Address) VALUES (12, 16);
INSERT INTO CustomerAddress (id_Customer, id_Address) VALUES (13, 17);
INSERT INTO CustomerAddress (id_Customer, id_Address) VALUES (14, 18);
INSERT INTO CustomerAddress (id_Customer, id_Address) VALUES (15, 19); 

------------------------------ CartProduct -------------------------------
INSERT INTO CartProduct(id_Cart, id_Product, quantity) VALUES (1, 10, 2);
INSERT INTO CartProduct(id_Cart, id_Product, quantity) VALUES (1, 21, 1);
INSERT INTO CartProduct(id_Cart, id_Product, quantity) VALUES (1, 32, 1);
INSERT INTO CartProduct(id_Cart, id_Product, quantity) VALUES (1, 43, 1);
INSERT INTO CartProduct(id_Cart, id_Product, quantity) VALUES (1, 54, 2);
INSERT INTO CartProduct(id_Cart, id_Product, quantity) VALUES (3, 60, 1);
INSERT INTO CartProduct(id_Cart, id_Product, quantity) VALUES (3, 70, 1);
INSERT INTO CartProduct(id_Cart, id_Product, quantity) VALUES (3, 21, 1);
INSERT INTO CartProduct(id_Cart, id_Product, quantity) VALUES (3, 11, 1);
INSERT INTO CartProduct(id_Cart, id_Product, quantity) VALUES (5, 31, 1);
INSERT INTO CartProduct(id_Cart, id_Product, quantity) VALUES (5, 62, 2);
INSERT INTO CartProduct(id_Cart, id_Product, quantity) VALUES (14, 63, 1);
INSERT INTO CartProduct(id_Cart, id_Product, quantity) VALUES (19, 67, 1);
INSERT INTO CartProduct(id_Cart, id_Product, quantity) VALUES (21, 12, 1);
INSERT INTO CartProduct(id_Cart, id_Product, quantity) VALUES (25, 44, 3);

------------------------ Wishlist --------------------------
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (1, 14);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (1, 17);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (1, 24);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (1, 10);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (1, 11);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (1, 64);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (1, 21);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (8, 27);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (8, 55);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (9, 54);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (10, 37);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (12, 59);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (14, 69);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (17, 11);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (19, 12);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (21, 34);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (23, 21);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (23, 33);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (24, 24);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (25, 18);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (27, 44);
INSERT INTO Wishlist(id_Customer, id_Product) VALUES (27, 57);


---------------------------------------------Review ----------------------------------------------
INSERT INTO Review(text, rating, id_Customer, id_Product) VALUES ('Very nice', 4, 5, 12);
INSERT INTO Review(text, rating, id_Customer, id_Product) VALUES ('Very bad', 1, 5, 13);
INSERT INTO Review(text, rating, id_Customer, id_Product) VALUES ('Very bad', 1, 5, 14);


-----------------------
--      INDEXES      --
-----------------------
CREATE INDEX order_user ON Purchase(id_Customer);
CLUSTER Purchase USING order_user;

CREATE INDEX product_price ON Product(price);

CREATE INDEX product_brand ON Product USING hash (brand);

CREATE INDEX cooler_type ON Cooler USING hash(type);

CREATE INDEX supply_type ON PowerSupply USING hash(type);

CREATE INDEX case_type ON PcCase USING hash(type);

CREATE INDEX storage_type ON Storage USING hash(type);

CREATE INDEX mb_type ON Motherboard USING hash(type);

CREATE INDEX review_author ON Review USING hash(id_Customer);

CREATE INDEX review_product ON Review (id_Product);
CLUSTER Purchase USING order_user;

CREATE INDEX review_rating ON Review(rating);


ALTER TABLE Product
ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION productSearchUpdate() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
            NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.name), 'A') ||
            setweight(to_tsvector('english', NEW.brand), 'B')
            );
    END IF;
    IF TG_OP = 'UPDATE' THEN
            IF (NEW.name <> OLD.name OR NEW.brand <> OLD.brand) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.name), 'A') ||
                setweight(to_tsvector('english', NEW.brand), 'B')
            );
            END IF;
    END IF;
    RETURN NEW;
END $$
LANGUAGE plpgsql;

-----------------------
--     TRIGGERS      --
-----------------------
CREATE TRIGGER productSearchUpdate
BEFORE INSERT OR UPDATE ON Product
FOR EACH ROW
EXECUTE PROCEDURE productSearchUpdate();

CREATE INDEX search_idx ON Product USING GIN (tsvectors);

--

-- TRIGGER TO UPDATE THE RATING OF A PRODUCT WHEN A REVIEW IS ADDED OR CHANGED --
CREATE FUNCTION updateProductRating() RETURNS TRIGGER AS
$BODY$
BEGIN
    Update Product
    Set rating = (SELECT avg(review.rating) from Review where id_Product = NEW.id_Product)
    where Product.id = NEW.id_Product;
	RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER updateProductRating 
AFTER INSERT OR UPDATE 
ON Review
FOR EACH ROW
EXECUTE PROCEDURE updateProductRating(); 

--

-- TRIGGER TO PREVENT A USER FROM ADDING MORE ITEMS TO THE CART THAN THE AVAILABLE STOCK
CREATE FUNCTION verificaStock() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NOT EXISTS(
        SELECT * from Product
        WHERE NEW.id_Product = Product.id
            AND NEW.quantity <= Product.stock
    )
    THEN RAISE EXCEPTION 'You can NOT add that much quantity to the cart';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verificaStock 
BEFORE INSERT ON CartProduct
FOR EACH ROW
EXECUTE PROCEDURE verificaStock(); 

--    

-- TRIGGER TO BLOCK BANNED USERS FROM WRITING REVIEWS
CREATE FUNCTION blockBannedUsers() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NOT EXISTS(
        SELECT * from Customer
        WHERE Customer.id = New.id_Customer
            AND Customer.id in (
                SELECT id 
                from "User"
                where isBanned = false
            )
    )
    THEN RAISE EXCEPTION 'You can NOT write reviews as you´ve been banned from the website';
    END IF;
    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER blockBannedUsers
AFTER INSERT ON Review
FOR EACH ROW
EXECUTE PROCEDURE blockBannedUsers();

--

-- TRIGGER TO SEND A NOTIFICATION TO THE Customer WHEN THERE IS A CHANGE OF STATUS IN THEIR ORDER
CREATE FUNCTION orderStatusNotification() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (
        SELECT * from Customer 
        where New.id_Customer = Customer.id 
    )
    THEN INSERT INTO Notification(content, id_User) VALUES ('Your order status has been updated' , New.id_Customer);
    END IF;
    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER orderStatusNotification
AFTER UPDATE ON Purchase
FOR EACH ROW
EXECUTE PROCEDURE orderStatusNotification(); 


-- TRIGGER TO CREATE A CART WHEN A NEW USER IS CREATED
CREATE FUNCTION cartCustomer() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO Cart(isActive, id_Customer) VALUES (DEFAULT, NEW.id);
    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER cartCustomer
AFTER INSERT ON Customer
FOR EACH ROW
EXECUTE PROCEDURE cartCustomer(); 


-- TRIGGER TO CREATE A CART WHEN A NEW USER IS CREATED AND TRASH THE OLD ONE
CREATE FUNCTION renewCart() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE Cart 
    SET isActive = false 
    WHERE id_Customer = NEW.id_Customer AND isActive = true; 

    INSERT INTO Cart VALUES (DEFAULT, true, NEW.id_Customer);
    
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER renewCart
AFTER INSERT ON Purchase
FOR EACH ROW
EXECUTE PROCEDURE renewCart(); 




-- TRIGGER TO SEND A NOTIFICATION TO THE Customer WHEN THEIR PAYMENT HAS BEEN APPROVED BY THE STORE
CREATE FUNCTION paymentApproved() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF New.orderStatus = 'Accepted' THEN
    INSERT INTO Notification(content, id_User) VALUES ('Your payment method has been approved by the store' , New.id_Customer);
    END IF;
    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER paymentApproved
AFTER UPDATE ON Purchase
FOR EACH ROW
EXECUTE PROCEDURE paymentApproved(); 


CREATE FUNCTION newPurchase() RETURNS TRIGGER AS
$BODY$
BEGIN
    INSERT INTO Notification(content, id_User) 
    select 
        'There has been a new purchase', id
    from "User" where isAdmin = true;
    
    RETURN NULL;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER newPurchase 
AFTER INSERT ON Purchase
FOR EACH ROW
EXECUTE PROCEDURE newPurchase();


-- Trigger to send a notification when the price of a product in the wishlist has decreased 
CREATE FUNCTION priceChangeWishlist() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF OLD.price > NEW.price                                                -- Is the item cheaper after the update?
    AND                                                                     
    EXISTS (                                                                -- And this product is in a wishlist?
        SELECT *                                                      
        from Wishlist  
        where id_Product = OLD.id                                               
    )
    THEN
    INSERT INTO NOTIFICATION (content, id_User) 
        select 'There has been a price decrease in one of your wishlisted products', id_Customer
        from Wishlist
        where id_Product = OLD.id;
    END IF;

RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER priceChangeWishlist                  
AFTER UPDATE ON Product                    -- Before updating a row in the product table
FOR EACH ROW                        
EXECUTE PROCEDURE priceChangeWishlist();    -- We will check if the new price is different from the old price 




-- Trigger to send a notification when the price of a product in the cart has decreased
CREATE FUNCTION priceChangeCart() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF OLD.price > NEW.price                                                -- Is the item cheaper after the update?
    AND                                                                     
    EXISTS (                                                                -- And this product is in a cart?
        SELECT *                                                      
        from CartProduct INNER JOIN Cart ON CartProduct.id_cart=Cart.id 
        where id_Product = OLD.id                                               
    )
    THEN
    INSERT INTO NOTIFICATION (content, id_User) 
        select 'There has been a price decrease in one of the products in your cart', id_Customer
        from CartProduct INNER JOIN Cart ON CartProduct.id_cart = Cart.id 
        where id_Product = OLD.id;
    END IF;

RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER priceChangeCart                  
AFTER UPDATE ON Product                         -- Before updating a row in the product table
FOR EACH ROW                        
EXECUTE PROCEDURE priceChangeCart();            -- We will check if the new price is different from the old price 



-- Trigger to send a notification when a product in the wishlist has become avaliable
CREATE FUNCTION productBackInStock() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF OLD.stock = 0                                                       -- Was the product out of stock?
    AND NEW.stock > 0                                                       -- And the stock is now greater than 0?           
    AND EXISTS (                                                                -- And this product is in a wishlist?
        SELECT *                                                      
        from Wishlist  
        where id_Product = OLD.id                                               
    )
    THEN

    INSERT INTO NOTIFICATION (content, id_User)
        select 'A product from your wishlist is back in stock', id_customer
        from Wishlist
        where id_Product = OLD.id;
    END IF;

RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER productBackInStock                  
AFTER UPDATE ON Product                    -- Before updating a row in the product table
FOR EACH ROW                        
EXECUTE PROCEDURE productBackInStock();    -- We will check if the new price is different from the old price */


/*
-----------------------
--    TRANSACTIONS   --
-----------------------
BEGIN TRANSACTION; 
SET TRANSACTION ISOLATION LEVEL SERIALIZABLE READ ONLY; 
  SELECT Cart.id, Product.name, CartProduct.quantity 
  FROM CartProduct INNER JOIN Product ON Product.id = CartProduct.id_Product 
  INNER JOIN Cart ON Cart.id = CartProduct.id_Cart; 
END TRANSACTION; */

