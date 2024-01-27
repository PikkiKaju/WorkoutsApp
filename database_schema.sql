-- Create database
CREATE DATABASE IF NOT EXISTS workout_data;

-- Use the database
USE workout_data;


-- Create table for esercises list
CREATE TABLE IF NOT EXISTS `_exercises_list` (
  `ExerciseID` smallint(6) NOT NULL,
  `ExerciseName` text NOT NULL,
  `ExerciseShowPosition` int(10) UNSIGNED NOT NULL,
  `ExercisePopularity` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Indexes for table `_exercises_list`
ALTER TABLE `_exercises_list`
  ADD PRIMARY KEY (`ExerciseID`);
COMMIT;


-- Create table for workouts list
CREATE TABLE IF NOT EXISTS `_workout_list` (
  `WorkoutID` smallint(6) NOT NULL,
  `WorkoutTableName` varchar(50) NOT NULL,
  `WorkoutDate` date NOT NULL,
  `WorkoutName` varchar(50) NOT NULL,
  `NumberOfExercises` smallint(11) NOT NULL,
  `WorkoutDescription` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Create table for a single workout data
CREATE TABLE `01_01_01_2000` (
  `ExerciseID` int(11) NOT NULL,
  `ExerciseName` varchar(50) NOT NULL,
  `Weights` varchar(20) NOT NULL,
  `NumberOfRepetitions` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Indexes for table `01_01_01_2000`
ALTER TABLE `01_01_01_2000`
  ADD PRIMARY KEY (`ExerciseID`);

-- AUTO_INCREMENT for table `01_01_01_20004`
ALTER TABLE `01_01_01_2000`
  MODIFY `ExerciseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;