# Open-Locker

[![Discord](https://img.shields.io/discord/1330191581273260113?style=flat-square&logo=discord&label=Discord&labelColor=%23FFF)](https://discord.gg/rZ74RYKN3H)

This is an open source project to create software that locks and unlocks public lockers to store and/or share items sponsored by Smart City Hameln-Pyrmont.
The group came together with the goal to improve their knowledge while building something that will be of immediate use to the people around them.

Within Hameln-Pyrmont, there is a set of lockers that the county uses to lend objects like laptops or VR headset to interested citizens. This project is supposed to improve the user experience and offer the county a way to individualize the software to better suit their needs.

We're still in the process of setting up the project, so feel free to return in a couple of weeks to see how far we've come!

## Monorepo Structure

This project is organized as a monorepo, which means it contains multiple projects within a single repository. Currently, the main project in this repository is:

- `locker-backend`: The backend application built with Laravel.

Using a monorepo allows us to manage all related projects in one place, making it easier to share code and manage dependencies.


## Setting Up Git Hooks

To ensure code quality and consistency, we use Git hooks in this project. Follow these steps to set up the Git hooks:

1. Run the `install-hooks.sh` script to configure the Git hooks path:
    ```sh
    ./install-hooks.sh
    ```

This script will set the Git hooks path to the `.githooks` directory in the project.

2. Verify that the hooks are set up correctly by checking the Git configuration:
    ```sh
    git config core.hooksPath
    ```

You should see `.githooks` as the output.

Now, the Git hooks are configured and will run automatically during the commit process.
