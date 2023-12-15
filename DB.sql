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
    heading TEXT NOT NULL,
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
    user_id INT UNIQUE,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
CREATE TABLE IF NOT EXISTS banned_users (
    ban_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    super_admin_id INT,
    reason TEXT,
    ban_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (super_admin_id) REFERENCES super_admins(super_admin_id)
);


-- Insert Users
INSERT INTO users (username, password, email, registration_date) VALUES
('John', '0b14d501a594442a01c6859541bcb3e8164d183d32937b851835442f69d5c94e', 'john@example.com', '2023-02-09 04:59:34'),
('Mark', '6cf615d5bcaac778352a8f1f3360d23f02f34ec182e259897fd6ce485d7870d4', 'mark@example.com', '2023-11-09 20:52:31'),
('Isabelle', '5906ac361a137e2d286465cd6588ebb5ac3f5ae955001100bc41577c3d751764', 'isabelle@example.com', '2023-08-20 17:12:09'),
('Anna', 'b97873a40f73abedd8d685a7cd5e5f85e4a9cfb83eac26886640a0813850122b', 'anna@example.com', '2023-06-01 05:08:06'),
('Gosho', '8b2c86ea9cf2ea4eb517fd1e06b74f399e7fec0fef92e3b482a6cf2e2b092023', 'gosho@example.com', '2023-08-01 07:34:50');

-- Insert Posts
INSERT INTO posts (user_id, heading, caption, timestamp) VALUES
(1, 'Post Heading 1', 'This is post 1 content.', '2023-11-02 18:10:48'),
(2, 'Post Heading 2', 'This is post 2 content.', '2023-06-10 03:00:59'),
(3, 'Post Heading 3', 'This is post 3 content.', '2023-06-09 22:01:46'),
(4, 'Post Heading 4', 'This is post 4 content.', '2023-09-24 05:14:27'),
(5, 'Post Heading 5', 'This is post 5 content.', '2023-03-08 23:43:04');



-- Insert Likes
INSERT INTO likes (post_id, user_id, timestamp) VALUES
(8, 7, '2023-02-04 02:47:30'),
(10, 9, '2023-04-03 01:05:30'),
(3, 5, '2023-05-22 06:51:45'),
(9, 10, '2023-04-05 01:24:33'),
(7, 7, '2023-03-28 18:40:12');

-- Insert Comments
INSERT INTO comments (post_id, user_id, content, timestamp) VALUES
(7, 6, 'Comment content 0', '2023-03-07 00:06:19'),
(1, 3, 'Comment content 1', '2023-03-26 11:30:47'),
(1, 2, 'Comment content 2', '2023-10-11 13:05:39'),
(5, 4, 'Comment content 3', '2023-10-21 07:52:29'),
(4, 4, 'Comment content 4', '2023-05-13 02:38:14');

-- Insert Followers
INSERT INTO followers (follower_id, followed_id) VALUES
(10, 4), (3, 10), (6, 1), (10, 6), (6, 9);

-- Insert Banned Users
INSERT INTO banned_users (user_id, super_admin_id, ban_date) VALUES
(4, 1, '2023-09-14 05:44:04'),
(8, 1, '2023-05-15 15:16:37'),
(5, 1, '2023-07-05 15:01:51'),
(5, 1, '2023-02-04 04:28:42'),
(5, 1, '2023-11-25 23:00:58');

INSERT INTO photos (post_id, photo_path, timestamp) VALUES
(6, '/path/to/photo1.jpg', '2023-07-09 18:44:19'),
(2, '/path/to/photo2.jpg', '2023-12-22 21:59:55'),
(3, '/path/to/photo3.jpg', '2023-01-11 20:28:37'),
(4, '/path/to/photo4.jpg', '2023-08-17 08:24:02'),
(5, '/path/to/photo5.jpg', '2023-09-07 23:49:07');