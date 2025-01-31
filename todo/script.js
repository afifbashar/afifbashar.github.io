
// script.js

// Select DOM elements
const taskInput = document.getElementById('taskInput');
const addTaskBtn = document.getElementById('addTaskBtn');
const taskList = document.getElementById('taskList');

// Event listener for adding a task
addTaskBtn.addEventListener('click', addTask);

// Event listener for pressing "Enter" key in the input field
taskInput.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        addTask();
    }
});

// Function to add a new task
function addTask() {
    const taskText = taskInput.value.trim();

    if (taskText === '') {
        alert('Please enter a task!');
        return;
    }

    // Create a new list item
    const li = document.createElement('li');
    li.textContent = taskText;

    // Create a delete button
    const deleteBtn = document.createElement('button');
    deleteBtn.textContent = 'Delete';
    deleteBtn.classList.add('delete-btn');

    // Add event listener to delete the task
    deleteBtn.addEventListener('click', function () {
        taskList.removeChild(li);
    });

    // Add event listener to mark task as complete
    li.addEventListener('click', function () {
        li.classList.toggle('completed');
    });

    // Append the delete button to the list item
    li.appendChild(deleteBtn);

    // Append the list item to the task list
    taskList.appendChild(li);

    // Clear the input field
    taskInput.value = '';
}
