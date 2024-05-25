<!-- PROJECT BADGES -->
<div align="center">

[![Poggit CI][poggit-ci-badge]][poggit-ci-url]
[![Stars][stars-badge]][stars-url]
[![License][license-badge]][license-url]

</div>

<!-- PROJECT LOGO -->
<br />
<div align="center">
  <img src="https://raw.githubusercontent.com/presentkim-pm/await-command/main/assets/icon.png" alt="Logo" width="80" height="80"/>
  <h3>await-command</h3>
  <p align="center">
    Provides generator-base command class for await-generator!

[View in Poggit][poggit-ci-url] · [Report a bug][issues-url] · [Request a feature][issues-url]

  </p>
</div>


<!-- ABOUT THE PROJECT -->

## About The Project

:heavy_check_mark: Provides util functions for serialize nbt tag

- `kim\present\serializer\nbt\NbtSerializer::toBinary(Tag $tag) : string`
- `kim\present\serializer\nbt\NbtSerializer::toBase64(Tag $tag) : string`
- `kim\present\serializer\nbt\NbtSerializer::toHex(Tag $tag) : string`
- `kim\present\serializer\nbt\NbtSerializer::toSnbt(Tag $tag) : string`

:heavy_check_mark: Provides util function for deserialize nbt tag

- `kim\present\serializer\nbt\NbtSerializer::fromBinary(string $contents) : Tag`
- `kim\present\serializer\nbt\NbtSerializer::fromBase64(string $contents) : Tag`
- `kim\present\serializer\nbt\NbtSerializer::fromHex(string $contents) : Tag`
- `kim\present\serializer\nbt\NbtSerializer::fromSnbt(string $contents) : Tag`

-----

## Installation

See [Official Poggit Virion Documentation](https://github.com/poggit/support/blob/master/virion.md)

-----

## How to use?

See [Main Document](https://github.com/presentkim-pm/await-command/blob/main/docs/README.md)

-----

## License

Distributed under the **MIT**. See [LICENSE][license-url] for more information


[poggit-ci-badge]: https://poggit.pmmp.io/ci.shield/presentkim-pm/await-command/await-command?style=for-the-badge

[stars-badge]: https://img.shields.io/github/stars/presentkim-pm/await-command.svg?style=for-the-badge

[license-badge]: https://img.shields.io/github/license/presentkim-pm/await-command.svg?style=for-the-badge

[poggit-ci-url]: https://poggit.pmmp.io/ci/presentkim-pm/await-command/await-command

[stars-url]: https://github.com/presentkim-pm/await-command/stargazers

[issues-url]: https://github.com/presentkim-pm/await-command/issues

[license-url]: https://github.com/presentkim-pm/await-command/blob/main/LICENSE

[project-icon]: https://raw.githubusercontent.com/presentkim-pm/await-command/main/assets/icon.png
