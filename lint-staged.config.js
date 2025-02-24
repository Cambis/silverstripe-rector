/** @type {import('lint-staged').Config} */
module.exports = {
  '!(e2e/**/*.php)*.php': [
    'php vendor/bin/parallel-lint --colors --blame',
    'php vendor/bin/ecs check --fix --ansi',
  ],
  '!(e2e/*/composer.json)composer.json': [
    'composer normalize --ansi'
  ],
};
