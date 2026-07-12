-- Users Table
INSERT INTO users (id, username, email, password, role) VALUES
(1, 'Ali Edris', 'alisameh714@gmail.com', '$2y$10$/ECY2JODbI5R3ojhYxUO.eXVJMja5RC2q13gUXtk63SXCGkdyKGLS', 'admin'),
(2, 'user1', 'user1@library.com', '$2y$10$r2UdDjwanTGPzUnfxNF7Q.NJBPYF7Ter1dqMBcz/.3c3YehYcmdgi', 'user'),
(3, 'user2', 'user2@library.com', '$2y$10$r7eu9lXlDgC2KVhrnMT/2eNeksoLnpm2mdYpEpIB.sxcb048SyFFC', 'user');

-- Books Table
INSERT INTO books (id, title, author, description, price, category, language, pages, book_file, book_image, status, uploaded_by) VALUES
-- Approved Books
(1, 'Atomic Habits', 'James Clear', 'A guide to building good habits and breaking bad ones.', 16.99, 'Self-Help', 'English', 320, './assets/uploads/books/Atomic_habits.pdf', './assets/uploads/images/atomic-habits_-tiny-changes-remarkable-results-james-clear.jpg', 'approved', 2),
(2, 'Getting Things Done', 'David Allen', 'The art of stress-free productivity.', 14.99, 'Self-Help', 'English', 267, './assets/uploads/books/getting-things-done-the-art-of-stress-free-productivity.pdf', './assets/uploads/images/getting-things-done-the-art-of-stress-free-productivit.jpg', 'approved', 3),
(3, 'Pür-Dikkat', 'Cal Newport', 'Focus and deep work in a distracted world.', 13.99, 'Self-Help', 'Turkish', 288, './assets/uploads/books/Pür-dikkat.pdf', './assets/uploads/images/Pur-Dikkat.jpg', 'approved', 2),
(4, 'الرحيق المختوم', 'صفي الرحمن المباركفوري', 'Biography of Prophet Muhammad (PBUH).', 10.99, 'Islamic Studies', 'Arabic', 340, './assets/uploads/books/الرحيق-المختوم.pdf', './assets/uploads/images/Al-Rahiq-Al-Makhtum.jpg', 'approved', 2),
(5, 'The Psychology of Money', 'Morgan Housel', 'Timeless lessons on wealth, greed, and happiness.', 18.99, 'Finance', 'English', 252, './assets/uploads/books/The_Psychology_of_Money.pdf', './assets/uploads/images/The_Psychology_of_Money.jpg', 'approved', 3),
(6, 'العقيدة في ضوء الكتاب والسنة : 4 الرسل والرسالات', 'د. عمر سليمان الأشقر', 'Theology in light of the Quran and Sunnah.', 11.99, 'Islamic Studies', 'Arabic', 310, './assets/uploads/books/العقيدة-في-ضوء-الكتاب-والسنة-4-الرسل-والرسالات.pdf', './assets/uploads/images/Al-Aqeeda-4-Al-Rusul.jpg', 'approved', 3),
(7, 'The 4-Hour Workweek', 'Tim Ferriss', 'Escape 9-5, live anywhere, and join the new rich.', 19.99, 'Self-Help', 'English', 308, './assets/uploads/books/the-4-hour-workweek.pdf', './assets/uploads/images/the-4-hour-workweek.jpg', 'approved', 2),

-- Approved Books (continued)
(8, ‘The Millionaire Next Door’, ‘Thomas J. Stanley’, ‘The surprising secrets of America\’s wealthy.’, 15.99, ‘Finance’, ‘English’, 275, ‘./assets/uploads/books/The-Millionaire-Next-Door.pdf’, ‘./assets/uploads/images/The-Millionaire-Next-Door.jpg’, ‘approved’, 3),
(9, ‘Your Money Or Your Life’, ‘Vicki Robin’, ‘Transform your relationship with money and achieve financial independence.’, 17.99, ‘Finance’, ‘English’, 384, ‘./assets/uploads/books/Your-Money-Or-Your-Life.pdf’, ‘./assets/uploads/images/Your-Money-Or-Your-Life.jpg’, ‘approved’, 3),
(10, ‘Think and Grow Rich’, ‘Napoleon Hill’, ‘The landmark bestseller now revised and updated for the 21st century. Unlock the secrets to wealth, success, and prosperity with Hill\’s timeless principles.’, 12.99, ‘Business’, ‘English’, 233, ‘./assets/uploads/books/Think-and-Grow-Rich.pdf’, ‘./assets/uploads/images/Think-and-Grow-Rich.jpg’, ‘approved’, 2),
(11, ‘Sapiens: A Brief History of Humankind’, ‘Yuval Noah Harari’, ‘A groundbreaking narrative of humanity\’s creation and evolution that explores how biology and history have defined us and enhanced our understanding of what it means to be human.’, 21.99, ‘History’, ‘English’, 443, ‘./assets/uploads/books/Sapiens.pdf’, ‘./assets/uploads/images/Sapiens.jpg’, ‘approved’, 3),
(12, ‘The Alchemist’, ‘Paulo Coelho’, ‘A magical story about Santiago, an Andalusian shepherd boy who yearns to travel the world in search of treasure as extravagant as any ever found. A timeless tale of following your dreams.’, 11.99, ‘Fiction’, ‘English’, 197, ‘./assets/uploads/books/The-Alchemist.pdf’, ‘./assets/uploads/images/The-Alchemist.jpg’, ‘approved’, 2),
(13, ‘Deep Work’, ‘Cal Newport’, ‘Rules for focused success in a distracted world. The ability to perform deep work is becoming increasingly rare at exactly the same time it is becoming increasingly valuable.’, 16.99, ‘Self-Help’, ‘English’, 296, ‘./assets/uploads/books/Deep-Work.pdf’, ‘./assets/uploads/images/Deep-Work.jpg’, ‘approved’, 3),
(14, ‘Rich Dad Poor Dad’, ‘Robert T. Kiyosaki’, ‘What the rich teach their kids about money that the poor and middle class do not! This bestselling classic has challenged and changed the way tens of millions of people think about money.’, 14.99, ‘Finance’, ‘English’, 207, ‘./assets/uploads/books/Rich-Dad-Poor-Dad.pdf’, ‘./assets/uploads/images/Rich-Dad-Poor-Dad.jpg’, ‘approved’, 2),
(15, ‘A Brief History of Time’, ‘Stephen Hawking’, ‘A landmark volume in science writing by one of the great minds of our time, Stephen Hawking\’s book explores topics such as space and time, black holes, the big bang, and the nature of the universe.’, 15.99, ‘Science’, ‘English’, 212, ‘./assets/uploads/books/A-Brief-History-of-Time.pdf’, ‘./assets/uploads/images/A-Brief-History-of-Time.jpg’, ‘approved’, 3),
(16, ‘1984’, ‘George Orwell’, ‘A dystopian social science fiction novel and cautionary tale about the dangers of totalitarianism. Winston Smith lives in a world of perpetual war, omnipresent government surveillance, and propaganda.’, 10.99, ‘Fiction’, ‘English’, 328, ‘./assets/uploads/books/1984.pdf’, ‘./assets/uploads/images/1984.jpg’, ‘approved’, 2),
(17, ‘The 7 Habits of Highly Effective People’, ‘Stephen R. Covey’, ‘Powerful lessons in personal change. One of the most inspiring and impactful books ever written, presenting a holistic, integrated, principle-centered approach for solving personal and professional problems.’, 18.99, ‘Self-Help’, ‘English’, 381, ‘./assets/uploads/books/The-7-Habits.pdf’, ‘./assets/uploads/images/The-7-Habits.jpg’, ‘approved’, 3),
(18, ‘Zero to One’, ‘Peter Thiel’, ‘Notes on startups, or how to build the future. Every moment in business happens only once. The next Bill Gates will not build an operating system, and the next Larry Page won\’t make a search engine.’, 19.99, ‘Business’, ‘English’, 195, ‘./assets/uploads/books/Zero-to-One.pdf’, ‘./assets/uploads/images/Zero-to-One.jpg’, ‘approved’, 2),
(19, ‘Meditations’, ‘Marcus Aurelius’, ‘A series of personal writings by the Roman Emperor Marcus Aurelius recording his private notes to himself and ideas on Stoic philosophy. A timeless guide to living a virtuous and meaningful life.’, 9.99, ‘Philosophy’, ‘English’, 254, ‘./assets/uploads/books/Meditations.pdf’, ‘./assets/uploads/images/Meditations.jpg’, ‘approved’, 3),
(20, ‘The Art of War’, ‘Sun Tzu’, ‘An ancient Chinese military treatise dating from the 5th century BC, attributed to the ancient Chinese military strategist Sun Tzu. One of the most influential texts on strategy and leadership.’, 8.99, ‘Philosophy’, ‘English’, 68, ‘./assets/uploads/books/The-Art-of-War.pdf’, ‘./assets/uploads/images/The-Art-of-War.jpg’, ‘approved’, 2),
(21, ‘Homo Deus: A Brief History of Tomorrow’, ‘Yuval Noah Harari’, ‘With the same penetrating intelligence that made Sapiens a global phenomenon, Harari explores humanity\’s future and the next steps of evolution, artificial intelligence, and the god-like powers humans may acquire.’, 22.99, ‘Science’, ‘English’, 450, ‘./assets/uploads/books/Homo-Deus.pdf’, ‘./assets/uploads/images/Homo-Deus.jpg’, ‘approved’, 3),
(22, ‘The Lean Startup’, ‘Eric Ries’, ‘How today\’s entrepreneurs use continuous innovation to create radically successful businesses. A new approach to developing and launching successful products and companies.’, 17.99, ‘Business’, ‘English’, 336, ‘./assets/uploads/books/The-Lean-Startup.pdf’, ‘./assets/uploads/images/The-Lean-Startup.jpg’, ‘approved’, 2),
(23, ‘إحياء علوم الدين’, ‘الإمام أبو حامد الغزالي’, ‘من أعظم الكتب الإسلامية في علم الأخلاق والتصوف والفقه، يتناول فيه الغزالي أساليب تزكية النفس وتهذيب الأخلاق والتقرب إلى الله عز وجل.’, 13.99, ‘Islamic Studies’, ‘Arabic’, 520, ‘./assets/uploads/books/Ihya-Ulum-Al-Din.pdf’, ‘./assets/uploads/images/Ihya-Ulum-Al-Din.jpg’, ‘approved’, 3),
(24, ‘مختصر صحيح البخاري’, ‘الإمام البخاري - مختصر الزبيدي’, ‘مختصر الجامع الصحيح المسند من حديث رسول الله صلى الله عليه وسلم، يحتوي على أصح الأحاديث النبوية الشريفة مرتبة على أبواب فقهية منظمة.’, 12.99, ‘Islamic Studies’, ‘Arabic’, 480, ‘./assets/uploads/books/Mukhtasar-Sahih-Bukhari.pdf’, ‘./assets/uploads/images/Mukhtasar-Sahih-Bukhari.jpg’, ‘approved’, 2),

-- Pending Book
(25, ‘Can\’t Hurt Me’, ‘David Goggins’, ‘Master your mind and defy the odds. Through jaw-dropping storytelling, Goggins takes you through the story of his unthinkable life and teaches you how to unlock your true potential.’, 20.99, ‘Self-Help’, ‘English’, 364, ‘./assets/uploads/books/Cant-Hurt-Me.pdf’, ‘./assets/uploads/images/Cant-Hurt-Me.jpg’, ‘pending’, 3),

-- Rejected Book
(26, ‘The Secret’, ‘Rhonda Byrne’, ‘The secret has existed throughout the history of humankind, based on the law of attraction which claims that thoughts can change a person\’s life directly.’, 13.99, ‘Self-Help’, ‘English’, 198, ‘./assets/uploads/books/The-Secret.pdf’, ‘./assets/uploads/images/The-Secret.jpg’, ‘rejected’, 2);

-- Comments Table
INSERT INTO comments (id, book_id, user_id, comment, created_at) VALUES
(1,  1,  2, 'An incredible read! Changed how I think about habits entirely. Highly recommend.', NOW()),
(2,  2,  3, 'GTD is the best productivity system I have ever used. Very practical and relevant.', NOW()),
(3,  3,  2, 'Cal Newport writes so clearly. Pür-Dikkat helped me focus on what truly matters.', NOW()),
(4,  4,  3, 'الرحيق المختوم من أجمل ما قرأت في السيرة النبوية. كتاب رائع ومليء بالمعلومات.', NOW()),
(5,  5,  2, 'Morgan Housel explains financial psychology in a way that everyone can understand. A must-read.', NOW()),
(6,  7,  3, 'The 4-Hour Workweek opened my eyes to lifestyle design and remote work. Life-changing!', NOW()),
(7,  10, 2, 'Think and Grow Rich is a classic for a reason. Every entrepreneur should read this.', NOW()),
(8,  11, 3, 'Sapiens is breathtaking in scope. Harari makes history feel alive and deeply relevant.', NOW()),
(9,  12, 2, 'The Alchemist is pure magic. Short but incredibly profound and uplifting.', NOW()),
(10, 14, 3, 'Rich Dad Poor Dad completely changed my mindset about money and investing.', NOW()),
(11, 15, 2, 'Hawking makes the most complex ideas in physics feel approachable. Brilliant book.', NOW()),
(12, 16, 3, '1984 is as relevant today as ever. A sobering and powerful warning about power and control.', NOW()),
(13, 17, 2, 'The 7 Habits gave me a clear framework for living with intention. Truly effective.', NOW()),
(14, 18, 3, 'Zero to One changed how I think about innovation and building something unique.', NOW()),
(15, 19, 2, 'Meditations is timeless wisdom. Marcus Aurelius speaks to you across two thousand years.', NOW()),
(16, 23, 3, 'إحياء علوم الدين كنز لا يقدر بثمن. كل مسلم يريد تزكية نفسه يجب أن يقرأه.', NOW()),
(17, 24, 2, 'مختصر صحيح البخاري مرجع أساسي في كل بيت مسلم. جزاهم الله خيراً على هذا الجهد.', NOW());
