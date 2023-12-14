CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    caption TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE IF NOT EXISTS photos (
    photo_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,  -- Link photo to the post
    photo_path VARCHAR(255) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(post_id)
);


CREATE TABLE IF NOT EXISTS likes (
    like_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    user_id INT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(post_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE IF NOT EXISTS comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    user_id INT,
    content TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(post_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
CREATE TABLE IF NOT EXISTS followers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    follower_id INT,
    followed_id INT,
    FOREIGN KEY (follower_id) REFERENCES users(user_id),
    FOREIGN KEY (followed_id) REFERENCES users(user_id)
);
CREATE TABLE IF NOT EXISTS super_admins (
    super_admin_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,  -- Assuming a super admin is also a user
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,  -- Password should be securely hashed
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
CREATE TABLE IF NOT EXISTS banned_users (
    ban_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,  -- ID of the banned user
    super_admin_id INT,  -- ID of the SuperAdmin who banned the user
    reason TEXT,  -- Reason for banning
    ban_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (super_admin_id) REFERENCES super_admins(super_admin_id)
);

