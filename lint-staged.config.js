export default {
    '**/*': 'prettier --check --ignore-unknown',
    '**/*.js': ['eslint'],
    '**/*.php,!.phpstorm.meta.php,!_ide_helper.php,!_ide_helper_models.php': './vendor/bin/duster lint',
};
