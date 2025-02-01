export default {
    '**/*': 'prettier --check --ignore-unknown',
    '**/*.js': ['eslint'],
    '**/*.php': './vendor/bin/duster lint',
};
