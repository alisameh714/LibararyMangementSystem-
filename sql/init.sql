-- SQLite schema + seed data
-- Auto-executed by db.php on first run

PRAGMA foreign_keys = ON;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id        INTEGER PRIMARY KEY AUTOINCREMENT,
    username  TEXT    NOT NULL,
    email     TEXT    NOT NULL UNIQUE,
    password  TEXT    NOT NULL,
    role      TEXT    NOT NULL DEFAULT 'user' CHECK(role IN ('admin','user')),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Books Table
CREATE TABLE IF NOT EXISTS books (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    title       TEXT    NOT NULL,
    author      TEXT    NOT NULL,
    description TEXT,
    book_file   TEXT    NOT NULL,
    book_image  TEXT    NOT NULL,
    price       REAL,
    status      TEXT    DEFAULT 'pending' CHECK(status IN ('pending','approved','rejected')),
    uploaded_by INTEGER,
    category    TEXT    NOT NULL DEFAULT 'General',
    language    TEXT    NOT NULL DEFAULT 'English',
    pages       INTEGER NOT NULL DEFAULT 0,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Comments Table
CREATE TABLE IF NOT EXISTS comments (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    book_id    INTEGER,
    user_id    INTEGER,
    comment    TEXT    NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ── Seed Data ────────────────────────────────────────────────

INSERT INTO users (id, username, email, password, role) VALUES
(1, 'Ali Edris', 'alisameh714@gmail.com', '$2y$10$mLBwc3VSAFKFRDvaCq9Vnuqn8h5uJaGqU4NsJTtPE7B/YPkJWoVLG', 'admin'),
(2, 'user1',    'user1@library.com',      '$2y$10$Zp3Rqmz/1Q1kVhykfKgV/.XVvnV3O9c6R6efrIP.kCJu/DTzdeBIK', 'user'),
(3, 'user2',    'user2@library.com',      '$2y$10$QqfCBVyj2tV9B6g7sukBoOpSzfp8hQuN2KrSfarSyGEX2vf3S5H6O', 'user');

INSERT INTO books (id, title, author, description, price, category, language, pages, book_file, book_image, status, uploaded_by) VALUES
(1,  'Atomic Habits', 'James Clear', 'A guide to building good habits and breaking bad ones.', 16.99, 'Self-Help', 'English', 320, './assets/uploads/books/Atomic_habits.pdf', './assets/uploads/images/atomic-habits_-tiny-changes-remarkable-results-james-clear.jpg', 'approved', 2),
(2,  'Getting Things Done', 'David Allen', 'The art of stress-free productivity.', 14.99, 'Self-Help', 'English', 267, './assets/uploads/books/getting-things-done-the-art-of-stress-free-productivity.pdf', './assets/uploads/images/getting-things-done-the-art-of-stress-free-productivit.jpg', 'approved', 3),
(3,  'Pür-Dikkat', 'Cal Newport', 'Focus and deep work in a distracted world.', 13.99, 'Self-Help', 'Turkish', 288, './assets/uploads/books/Pür-dikkat.pdf', './assets/uploads/images/Pur-Dikkat.jpg', 'approved', 2),
(4,  'الرحيق المختوم', 'صفي الرحمن المباركفوري', 'Biography of Prophet Muhammad (PBUH).', 10.99, 'Islamic Studies', 'Arabic', 340, './assets/uploads/books/الرحيق-المختوم.pdf', './assets/uploads/images/Al-Rahiq-Al-Makhtum.jpg', 'approved', 2),
(5,  'The Psychology of Money', 'Morgan Housel', 'Timeless lessons on wealth, greed, and happiness.', 18.99, 'Finance', 'English', 252, './assets/uploads/books/The_Psychology_of_Money.pdf', './assets/uploads/images/The_Psychology_of_Money.jpg', 'approved', 3),
(6,  'العقيدة في ضوء الكتاب والسنة : 4 الرسل والرسالات', 'د. عمر سليمان الأشقر', 'Theology in light of the Quran and Sunnah.', 11.99, 'Islamic Studies', 'Arabic', 310, './assets/uploads/books/العقيدة-في-ضوء-الكتاب-والسنة-4-الرسل-والرسالات.pdf', './assets/uploads/images/Al-Aqeeda-4-Al-Rusul.jpg', 'approved', 3),
(7,  'The 4-Hour Workweek', 'Tim Ferriss', 'Escape 9-5, live anywhere, and join the new rich.', 19.99, 'Self-Help', 'English', 308, './assets/uploads/books/the-4-hour-workweek.pdf', './assets/uploads/images/the-4-hour-workweek.jpg', 'approved', 2),
(8,  'The Millionaire Next Door', 'Thomas J. Stanley', 'The surprising secrets of America''s wealthy.', 15.99, 'Finance', 'English', 275, './assets/uploads/books/The-Millionaire-Next-Door.pdf', './assets/uploads/images/The-Millionaire-Next-Door.jpg', 'approved', 3),
(9,  'Your Money Or Your Life', 'Vicki Robin', 'Transform your relationship with money and achieve financial independence.', 17.99, 'Finance', 'English', 384, './assets/uploads/books/Your-Money-Or-Your-Life.pdf', './assets/uploads/images/Your-Money-Or-Your-Life.jpg', 'approved', 3),
(10, 'Think and Grow Rich', 'Napoleon Hill', 'The landmark bestseller now revised and updated for the 21st century. Unlock the secrets to wealth, success, and prosperity with Hill''s timeless principles.', 12.99, 'Business', 'English', 233, './assets/uploads/books/Think-and-Grow-Rich.pdf', './assets/uploads/images/Think-and-Grow-Rich.jpg', 'approved', 2),
(11, 'Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', 'A groundbreaking narrative of humanity''s creation and evolution.', 21.99, 'History', 'English', 443, './assets/uploads/books/Sapiens.pdf', './assets/uploads/images/Sapiens.jpg', 'approved', 3),
(12, 'The Alchemist', 'Paulo Coelho', 'A magical story about following your dreams.', 11.99, 'Fiction', 'English', 197, './assets/uploads/books/The-Alchemist.pdf', './assets/uploads/images/The-Alchemist.jpg', 'approved', 2),
(13, 'Deep Work', 'Cal Newport', 'Rules for focused success in a distracted world.', 16.99, 'Self-Help', 'English', 296, './assets/uploads/books/Deep-Work.pdf', './assets/uploads/images/Deep-Work.jpg', 'approved', 3),
(14, 'Rich Dad Poor Dad', 'Robert T. Kiyosaki', 'What the rich teach their kids about money.', 14.99, 'Finance', 'English', 207, './assets/uploads/books/Rich-Dad-Poor-Dad.pdf', './assets/uploads/images/Rich-Dad-Poor-Dad.jpg', 'approved', 2),
(15, 'A Brief History of Time', 'Stephen Hawking', 'Explores space, time, black holes, and the nature of the universe.', 15.99, 'Science', 'English', 212, './assets/uploads/books/A-Brief-History-of-Time.pdf', './assets/uploads/images/A-Brief-History-of-Time.jpg', 'approved', 3),
(16, '1984', 'George Orwell', 'A dystopian tale about totalitarianism and omnipresent government surveillance.', 10.99, 'Fiction', 'English', 328, './assets/uploads/books/1984.pdf', './assets/uploads/images/1984.jpg', 'approved', 2),
(17, 'The 7 Habits of Highly Effective People', 'Stephen R. Covey', 'Powerful lessons in personal change and principle-centered living.', 18.99, 'Self-Help', 'English', 381, './assets/uploads/books/The-7-Habits.pdf', './assets/uploads/images/The-7-Habits.jpg', 'approved', 3),
(18, 'Zero to One', 'Peter Thiel', 'Notes on startups, or how to build the future.', 19.99, 'Business', 'English', 195, './assets/uploads/books/Zero-to-One.pdf', './assets/uploads/images/Zero-to-One.jpg', 'approved', 2),
(19, 'Meditations', 'Marcus Aurelius', 'Personal writings on Stoic philosophy and virtuous living.', 9.99, 'Philosophy', 'English', 254, './assets/uploads/books/Meditations.pdf', './assets/uploads/images/Meditations.jpg', 'approved', 3),
(20, 'The Art of War', 'Sun Tzu', 'An ancient military treatise on strategy and leadership.', 8.99, 'Philosophy', 'English', 68, './assets/uploads/books/The-Art-of-War.pdf', './assets/uploads/images/The-Art-of-War.jpg', 'approved', 2),
(21, 'Homo Deus: A Brief History of Tomorrow', 'Yuval Noah Harari', 'Explores humanity''s future and the next steps of evolution and AI.', 22.99, 'Science', 'English', 450, './assets/uploads/books/Homo-Deus.pdf', './assets/uploads/images/Homo-Deus.jpg', 'approved', 3),
(22, 'The Lean Startup', 'Eric Ries', 'How entrepreneurs use continuous innovation to create successful businesses.', 17.99, 'Business', 'English', 336, './assets/uploads/books/The-Lean-Startup.pdf', './assets/uploads/images/The-Lean-Startup.jpg', 'approved', 2),
(23, 'إحياء علوم الدين', 'الإمام أبو حامد الغزالي', 'من أعظم الكتب الإسلامية في علم الأخلاق والتصوف والفقه.', 13.99, 'Islamic Studies', 'Arabic', 520, './assets/uploads/books/Ihya-Ulum-Al-Din.pdf', './assets/uploads/images/Ihya-Ulum-Al-Din.jpg', 'approved', 3),
(24, 'مختصر صحيح البخاري', 'الإمام البخاري - مختصر الزبيدي', 'مختصر الجامع الصحيح المسند من حديث رسول الله صلى الله عليه وسلم.', 12.99, 'Islamic Studies', 'Arabic', 480, './assets/uploads/books/Mukhtasar-Sahih-Bukhari.pdf', './assets/uploads/images/Mukhtasar-Sahih-Bukhari.jpg', 'approved', 2),
(25, 'Can''t Hurt Me', 'David Goggins', 'Master your mind and defy the odds.', 20.99, 'Self-Help', 'English', 364, './assets/uploads/books/Cant-Hurt-Me.pdf', './assets/uploads/images/Cant-Hurt-Me.jpg', 'pending', 3),
(26, 'The Secret', 'Rhonda Byrne', 'Based on the law of attraction which claims that thoughts can change your life.', 13.99, 'Self-Help', 'English', 198, './assets/uploads/books/The-Secret.pdf', './assets/uploads/images/The-Secret.jpg', 'rejected', 2);

INSERT INTO comments (id, book_id, user_id, comment) VALUES
(1,  1,  2, 'An incredible read! Changed how I think about habits entirely. Highly recommend.'),
(2,  2,  3, 'GTD is the best productivity system I have ever used. Very practical and relevant.'),
(3,  3,  2, 'Cal Newport writes so clearly. Pür-Dikkat helped me focus on what truly matters.'),
(4,  4,  3, 'الرحيق المختوم من أجمل ما قرأت في السيرة النبوية. كتاب رائع ومليء بالمعلومات.'),
(5,  5,  2, 'Morgan Housel explains financial psychology in a way that everyone can understand. A must-read.'),
(6,  7,  3, 'The 4-Hour Workweek opened my eyes to lifestyle design and remote work. Life-changing!'),
(7,  10, 2, 'Think and Grow Rich is a classic for a reason. Every entrepreneur should read this.'),
(8,  11, 3, 'Sapiens is breathtaking in scope. Harari makes history feel alive and deeply relevant.'),
(9,  12, 2, 'The Alchemist is pure magic. Short but incredibly profound and uplifting.'),
(10, 14, 3, 'Rich Dad Poor Dad completely changed my mindset about money and investing.'),
(11, 15, 2, 'Hawking makes the most complex ideas in physics feel approachable. Brilliant book.'),
(12, 16, 3, '1984 is as relevant today as ever. A sobering and powerful warning about power and control.'),
(13, 17, 2, 'The 7 Habits gave me a clear framework for living with intention. Truly effective.'),
(14, 18, 3, 'Zero to One changed how I think about innovation and building something unique.'),
(15, 19, 2, 'Meditations is timeless wisdom. Marcus Aurelius speaks to you across two thousand years.'),
(16, 23, 3, 'إحياء علوم الدين كنز لا يقدر بثمن. كل مسلم يريد تزكية نفسه يجب أن يقرأه.'),
(17, 24, 2, 'مختصر صحيح البخاري مرجع أساسي في كل بيت مسلم. جزاهم الله خيراً على هذا الجهد.');
