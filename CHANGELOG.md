# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [2.0.0]

### Added

- `Maybe::nothing` to construct a `Nothing` instance
- `Maybe::just` to construct a `Just` instance

### Removed

- `Just` and `Nothing` can no longer be constructed using `new`

### Changed

- `::of()` and `::empty()` have been marked `final` to prevent extension
- `Just` and `Nothing` have been marked `final` to prevent extension

## [1.0.0]

- Initial release
