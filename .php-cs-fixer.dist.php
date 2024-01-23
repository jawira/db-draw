<?php declare(strict_types=1);
$finder = \PhpCsFixer\Finder::create()->in(__DIR__ . DIRECTORY_SEPARATOR . 'src');
$config = new \PhpCsFixer\Config();

return $config->setRules([
  'no_unused_imports' => true,
  'single_blank_line_at_eof' => true,
  'no_trailing_whitespace' => true,
  'explicit_string_variable' => true,
  'heredoc_to_nowdoc' => true,
  'no_binary_string' => true,
  'single_quote' => true,
  'simple_to_complex_string_variable' => true,
  'braces' => true,
  'declare_strict_types' => true,
  'switch_case_space' => true,
  'visibility_required' => true,
  'simplified_if_return' => true,
])->setFinder($finder)->setHideProgress(true)->setIndent('  ');
