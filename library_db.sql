-- Create the database if it doesn't exist and then select it for use.
CREATE DATABASE IF NOT EXISTS `online_library` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `library_db`;

--
-- Table structure for table `users`
--
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `student_number` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `course` varchar(255) DEFAULT NULL,
  `campus` varchar(100) DEFAULT 'Lubaga',
  `role` enum('student','admin') NOT NULL DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
-- The password for this admin user is 'password123'
--
INSERT INTO `users` (`id`, `full_name`, `email`, `student_number`, `password`, `course`, `campus`, `role`, `created_at`) VALUES
(1, 'Admin User', 'admin@umu.ac.ug', 'ADMIN001', '$2y$10$IfaD9g.o9v2yHh.L6b9p.ukYjY5NAaCq3gUwaHotg64G.d5u/v2iS', 'System Administration', 'Lubaga', 'admin', '2023-09-27 10:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--
DROP TABLE IF EXISTS `materials`;
CREATE TABLE `materials` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author_lecturer` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `material_type` enum('book','pdf','video','powerpoint','past_paper') NOT NULL,
  `genre_course` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT 'default_book.png',
  `file_path` varchar(255) DEFAULT NULL,
  `total_copies` int(11) NOT NULL DEFAULT 1,
  `available_copies` int(11) NOT NULL DEFAULT 1,
  `publication_date` date DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping sample data for table `materials`
--
INSERT INTO `materials` (`id`, `title`, `author_lecturer`, `description`, `material_type`, `genre_course`, `cover_image`, `file_path`, `total_copies`, `available_copies`, `publication_date`, `publisher`, `pages`, `created_at`) VALUES
(1, 'Database System Concepts', 'Abraham Silberschatz', 'A comprehensive book on database management systems, covering ER models, relational models, SQL, and system architecture.', 'book', 'Computer Science', 'db_concepts.jpg', NULL, 5, 5, '2019-03-15', 'McGraw-Hill Education', 1376, '2023-09-26 12:01:00'),
(2, 'Introduction to Algorithms', 'Thomas H. Cormen', 'The bible of algorithms, providing a comprehensive and rigorous treatment of a wide range of algorithms in depth.', 'book', 'Computer Science', 'intro_algorithms.jpg', NULL, 3, 2, '2009-07-31', 'The MIT Press', 1312, '2023-09-26 12:02:00'),
(3, 'The Great Gatsby', 'F. Scott Fitzgerald', 'A novel about the American dream, set in the Jazz Age on Long Island.', 'book', 'Literature', 'gatsby.jpg', NULL, 10, 10, '1925-04-10', 'Charles Scribner''s Sons', 180, '2023-09-26 12:03:00'),
(4, 'Financial Accounting Basics', 'Dr. Jane Doe', 'An introductory guide to the principles of financial accounting for business students.', 'book', 'Business Administration', 'accounting.jpg', NULL, 8, 8, '2021-08-20', 'UMU Press', 450, '2023-09-26 12:04:00'),
(5, 'Data Structures & Algorithms Notes', 'Prof. John Kizza', 'Comprehensive lecture notes covering fundamental data structures like arrays, linked lists, stacks, queues, and trees.', 'pdf', 'Computer Science', 'pdf_icon.png', 'CS201_Notes.pdf', 1, 1, '2022-01-10', 'UMU Faculty of Science', 98, '2023-09-26 12:05:00');

-- --------------------------------------------------------

--
-- Table structure for table `borrowings`
--
DROP TABLE IF EXISTS `borrowings`;
CREATE TABLE `borrowings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `borrow_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `due_date` datetime NOT NULL, /* <-- THIS IS THE CORRECTED LINE */
  `return_date` timestamp NULL DEFAULT NULL,
  `status` enum('borrowed','returned','overdue') NOT NULL DEFAULT 'borrowed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_number` (`student_number`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `borrowings`
--
ALTER TABLE `borrowings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD CONSTRAINT `borrowings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `borrowings_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;


-- This script adds a comprehensive set of sample data for books and study materials.
-- Make sure you have selected your `online_library_db` database before running.

-- =================================================================
-- SAMPLE BOOKS (10 total)
-- For this data to look best, you should find and save corresponding
-- cover images (e.g., 'clean_code.jpg') in your 'uploads/book_covers/' folder.
-- =================================================================

-- 5 Computer Science Books
INSERT INTO `materials` (`title`, `author_lecturer`, `description`, `material_type`, `genre_course`, `cover_image`, `total_copies`, `available_copies`, `publication_date`, `publisher`, `pages`) VALUES
('Clean Code: A Handbook of Agile Software Craftsmanship', 'Robert C. Martin', 'A classic book on writing readable, maintainable, and robust code. Essential reading for any software developer.', 'book', 'Computer Science', 'clean_code.jpg', 8, 8, '2008-08-01', 'Prentice Hall', 464),
('Design Patterns: Elements of Reusable Object-Oriented Software', 'Erich Gamma, et al.', 'The "Gang of Four" book that introduces 23 fundamental design patterns for software engineering.', 'book', 'Computer Science', 'design_patterns.jpg', 6, 5, '1994-10-21', 'Addison-Wesley', 395),
('The Pragmatic Programmer: Your Journey to Mastery', 'David Thomas, Andrew Hunt', 'Covers a wide range of topics from personal responsibility and career development to architectural techniques for keeping your code flexible.', 'book', 'Computer Science', 'pragmatic_programmer.jpg', 10, 10, '2019-09-13', 'Addison-Wesley', 352),
('Computer Networking: A Top-Down Approach', 'James F. Kurose, Keith W. Ross', 'An excellent introduction to computer networking, explaining protocols and concepts from the application layer down to the physical layer.', 'book', 'Computer Science', 'networking.jpg', 12, 10, '2016-03-10', 'Pearson', 864),
('Structure and Interpretation of Computer Programs (SICP)', 'Harold Abelson, Gerald Jay Sussman', 'A foundational text in computer science that teaches fundamental principles of computation.', 'book', 'Computer Science', 'sicp.jpg', 5, 5, '1996-07-25', 'MIT Press', 657);

-- 5 Business Administration Books
INSERT INTO `materials` (`title`, `author_lecturer`, `description`, `material_type`, `genre_course`, `cover_image`, `total_copies`, `available_copies`, `publication_date`, `publisher`, `pages`) VALUES
('The Lean Startup', 'Eric Ries', 'How Today''s Entrepreneurs Use Continuous Innovation to Create Radically Successful Businesses.', 'book', 'Business Administration', 'lean_startup.jpg', 15, 15, '2011-09-13', 'Crown Business', 336),
('Good to Great: Why Some Companies Make the Leap... and Others Don''t', 'Jim Collins', 'A study on how good companies become great companies, and what distinguishes them from their competitors.', 'book', 'Business Administration', 'good_to_great.jpg', 7, 6, '2001-10-16', 'HarperBusiness', 320),
('Principles of Marketing', 'Philip T. Kotler, Gary Armstrong', 'The benchmark and leading textbook for undergraduate marketing courses.', 'book', 'Business Administration', 'marketing_principles.jpg', 20, 18, '2017-01-19', 'Pearson', 736),
('Thinking, Fast and Slow', 'Daniel Kahneman', 'A fascinating look into the two systems that drive the way we think, revealing the marvels and flaws of human intuition.', 'book', 'Business Administration', 'thinking_fast_slow.jpg', 9, 9, '2011-10-25', 'Farrar, Straus and Giroux', 512),
('Zero to One: Notes on Startups, or How to Build the Future', 'Peter Thiel, Blake Masters', 'A book on startup philosophy and business strategy, arguing for the importance of monopoly and innovation.', 'book', 'Business Administration', 'zero_to_one.jpg', 11, 11, '2014-09-16', 'Crown Business', 224);

-- =================================================================
-- SAMPLE STUDY MATERIALS (15 total)
-- For these, you can use generic icons like 'pdf_icon.png', 'video_icon.png', etc.
-- and save them in your 'uploads/book_covers/' folder.
-- =================================================================

-- 5 Computer Science Study Materials
INSERT INTO `materials` (`title`, `author_lecturer`, `description`, `material_type`, `genre_course`, `cover_image`, `file_path`, `total_copies`, `available_copies`) VALUES
('CS202: OOP Lecture Slides', 'Dr. A. Nabatanzi', 'Complete lecture slides for the Object-Oriented Programming course, covering encapsulation, inheritance, and polymorphism.', 'powerpoint', 'Computer Science', 'ppt_icon.png', 'CS202_OOP_Slides.pptx', 1, 1),
('CS305: Database Systems Tutorial Video', 'Prof. P. Okello', 'A video tutorial demonstrating how to design a relational database schema and perform normalization.', 'video', 'Computer Science', 'video_icon.png', 'CS305_DB_Tutorial.mp4', 1, 1),
('CS101: Intro to Programming Past Paper 2022', 'Faculty of Science', 'The final examination paper for the Introduction to Programming course from the May 2022 semester.', 'past_paper', 'Computer Science', 'past_paper_icon.png', 'CS101_PastPaper_2022.pdf', 1, 1),
('Advanced Algorithms Lecture Notes', 'Dr. M. Kizza', 'Comprehensive PDF notes covering dynamic programming, greedy algorithms, and graph theory.', 'pdf', 'Computer Science', 'pdf_icon.png', 'CS401_Advanced_Algos.pdf', 1, 1),
('Software Engineering Project Guide', 'Faculty of Science', 'A guide outlining the requirements and milestones for the final year software engineering project.', 'pdf', 'Computer Science', 'pdf_icon.png', 'SE_Project_Guide.pdf', 1, 1);

-- 5 Business Administration Study Materials
INSERT INTO `materials` (`title`, `author_lecturer`, `description`, `material_type`, `genre_course`, `cover_image`, `file_path`, `total_copies`, `available_copies`) VALUES
('BAM210: Macroeconomics Lecture Notes', 'Dr. S. Tumwine', 'A complete set of lecture notes for the macroeconomics course, focusing on GDP, inflation, and monetary policy.', 'pdf', 'Business Administration', 'pdf_icon.png', 'BAM210_Macro_Notes.pdf', 1, 1),
('BAM101: Business Law Case Studies', 'Prof. L. Atim', 'A collection of case studies used in the introductory Business Law course.', 'pdf', 'Business Administration', 'pdf_icon.png', 'BAM101_Case_Studies.pdf', 1, 1),
('BAM320: Entrepreneurship Video Series', 'Guest Lecturer', 'A series of video interviews with successful local entrepreneurs sharing their startup journeys.', 'video', 'Business Administration', 'video_icon.png', 'BAM320_Ent_Series.mp4', 1, 1),
('BAM405: Strategic Management Past Paper 2021', 'Faculty of Business', 'The final examination paper for Strategic Management from the October 2021 semester.', 'past_paper', 'Business Administration', 'past_paper_icon.png', 'BAM405_PastPaper_2021.pdf', 1, 1),
('Financial Accounting I - Presentation', 'Dr. J. Byamugisha', 'PowerPoint slides covering the basics of financial statements, debits, and credits.', 'powerpoint', 'Business Administration', 'ppt_icon.png', 'BAM105_Accounting_Slides.pptx', 1, 1);

-- 5 Law Study Materials
INSERT INTO `materials` (`title`, `author_lecturer`, `description`, `material_type`, `genre_course`, `cover_image`, `file_path`, `total_copies`, `available_copies`) VALUES
('LAW101: Intro to Ugandan Legal System', 'Prof. T. Owiny', 'Lecture notes providing an overview of the court structure and sources of law in Uganda.', 'pdf', 'Law', 'pdf_icon.png', 'LAW101_LegalSystem.pdf', 1, 1),
('LAW202: Criminal Law Moot Court Problem', 'Moot Court Society', 'The problem set for the annual inter-faculty moot court competition on criminal law.', 'past_paper', 'Law', 'past_paper_icon.png', 'LAW202_MootProblem.pdf', 1, 1),
('LAW305: Contract Law Key Cases', 'Dr. A. Kirunda', 'A summary of landmark contract law cases relevant to the Ugandan context.', 'pdf', 'Law', 'pdf_icon.png', 'LAW305_KeyCases.pdf', 1, 1),
('LAW205: Torts Lecture Slides', 'Prof. E. Mbabazi', 'A complete set of PowerPoint slides for the Law of Torts, covering negligence, defamation, and trespass.', 'powerpoint', 'Law', 'ppt_icon.png', 'LAW205_Torts_Slides.pptx', 1, 1),
('Advocacy Skills Workshop Video', 'Uganda Law Society', 'A recording of a practical workshop on courtroom etiquette and advocacy skills for law students.', 'video', 'Law', 'video_icon.png', 'Advocacy_Workshop.mp4', 1, 1);