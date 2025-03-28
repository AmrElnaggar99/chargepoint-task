# ChargePoint Software Engineering Task - Amr Elnaggar

## Description

This project is an implementation of Conway's Game of Life. The program simulates the evolution of a grid of cells based on a set of rules. The grid starts with an initial "seed" of live cells, and each generation is computed based on the number of live neighbors for each cell.

### Example

#### Input seed

```php
$currentGlider = [
    [0, 1],
    [1, 2],
    [2, 0],
    [2, 1],
    [2, 2]
];
```

#### Output Grid

```
	0	1	2
0	⬜	⬛	⬜
1	⬜	⬜	⬛
2	⬛	⬛	⬛

Live cells: [0, 1] [1, 2] [2, 0] [2, 1] [2, 2]
```

## Stack

- The project is written in PHP while following object-oriented programming (OOP) principles.
- Uses Composer for dependency management.
- The unit tests are using PHPUnit.

## Starting the project

### Package manager

This project uses [Composer](https://getcomposer.org/doc/00-intro.md) and the guide assumes you have it installed.

### Server

You can run this project using any PHP local server. However, the recommended approach is:

- Docker
- DDEV ([Installation Guide](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/))

### Running using DDEV

```bash
# 1. Clone the repository
git clone https://github.com/AmrElnaggar99/chargepoint-task.git
cd chargepoint-task


# 2. Install dependencies
composer install

# 3. Configure and start DDEV
ddev config
ddev start
ddev launch
```

Then you can visit your DDEV assigned URL

- For example: `chargepoint-task.ddev.site/`

## Testing the project

To test the project you only need to care about `index.php` at the root folder.

- Feel free to change `NUM_OF_GENERATIONS`
  - Note that large numbers can have I/O limitations since the output is in the browser.
- Feel free to change the initial seed variable `currentGlider`.

> [!NOTE]  
> Note that the output will print your initial seed as Generation 1.

## Design Decisions

> The coordinates in the grid do not follow a cartesian grid (where x is the column and y is the row) but an i and j indices (where i is the row and j is the column). Which I find more intuitive in programming.

### Input shape

I decided the implementation should only focus on the live cells and not the whole universe for two reasons:

1. The universe is infinite.
2. The universe's information is useless to the problem.
   > The universe can only be useful for the UI and not the logic, and the task document mentioned that there is no need for a UI.

### Output shape

Even though the task document mentions:

> To demonstrate that the program works you can print out the state of the universe to the console/output after each generation.

I decided to only print the bounding box of the live cells. Which can later be rendered at the middle of some infinite universe. However, Rendering the whole infinite universe is gonna be another problem.

### Edge cases possible:

- Reach a state of no live cells
  - The code will render a message “No live cells”.
- Reach a state of equilibrium (no more cells dying or living)
  - The code will keep generating the same generation for as long as requested.

### Structure

The implementation tries to follow the OOP principles, SOLID, Design Patterns like DRY, SoC as much as possible.
Therefore the following classes are used:

#### 1. CellKeyUtils & GridUtils

provide static methods usually repeated in the code.

> The DRY (Don't Repeat Yourself) principle states that every piece of knowledge or logic should have a single, unambiguous, and authoritative representation within a system to reduce redundancy and improve maintainability.

#### 2. GameRenderer

Isolates the presentation code from the logic code.

> The SoC (Separation of Concerns) is a design principle that advocates dividing a program into distinct sections, each addressing a separate concern or responsibility.

#### 3. Grid

Provides needed information about the grid around the glider pattern. This information will then be consumed by the `GameOfLife` and `GameRenderer` class.

#### 4. GameOfLife

Consumes a `Grid` instance and implements the logic of the Game of Life.

### Other principles implemented

#### Encapsulation

The `Grid` class encapsulates the grid's state and provides getters to access them.

#### Dependency Injection

The `GridRenderer` and `GameOfLife` classes both receive the `Grid` instance as a dependency via their constructors.
This allows the Grid class to be reused and makes the code more testable.

#### MVC-like

The `index.php` (de facto controller) orchestrates the interaction between the `GameOfLife` (de facto Model), and `GridRenderer` (de facto View).

### Complexity

In this implementation `n` is the number of live cells.

- Time complexity: `O(n)`.
- Space complexity: `O(n)`.

#### Note on space complexity

Even though we can achieve O(1) space complexity by modifying the seed in place instead of making a new `nextGeneration` array, I decided not to do that for the following two reasons:

1. Making a new array is advantageous for the readability of the code.
2. We don't expect a huge number of live cells so O(n) is not a bad tradeoff.

#### Note on time complexity

- In the method `GameOfLife::determineShapeOfNextGlider` we need to check if a given cell is live.
- If we store the live cells in an array, this `isLive` check will have complexity of `m*n` where `m` is the number of cells needed to be checked, and `n` is the size of live cells array.
- A better approach is implemented that converts the initial seed to a map instead of an array. So, the `isLive` check happen in `O(1)`, and building the map initially is gonna be `O(n)` where `n` is the number of live cells. Which is much better.

## Possible Improvements

- Use a Template Engine like twig or build an MVC with a React frontend.
- To better implement **separation of concerns**, the `GridRenderer` class should better be in the frontend (JS/TS) code and not in server code (PHP).
- In the UI we can add a button to dynamically generate the next generation.
- In the UI we can render a full infinite grid but as for the server, it should only return the live cells indices.
