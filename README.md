# BÖRD
- A very basic imageboard built to work on the LAMP stack along with a basic Vagrant VM setup to work with this for testing
- In development, meant as a project to help me revise my PHP skills for the school leaving exam
## Planned Features
- Users with Roles (Moderator, Admin, User) that also have the ability to have profile pictures and profiles
- Boards, Threads and comments with similar functionality to 4chan when it comes to the linking of posts & greentexts
- an Admin & Moderator dashboard
## Features I might add in the future
- Friends list
- 1 on 1 chatting
- Preffered boards

## How to setup locally
- cd into the ./vagrant/LAMP/ folder and run ``vagrant up`` to start up your vagrant box
- **wait for everything to set itself up**
- open up http://localhost:8080/phpmyadmin and import the sql file in src directory, I might automate this but maybe I won't, we'll see since Vagrant is only used so It's hosted in it's own environment.

