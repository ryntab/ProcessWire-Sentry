import resolve from '@rollup/plugin-node-resolve';
import commonjs from '@rollup/plugin-commonjs';
import { terser } from 'rollup-plugin-terser';

export default {
  input: '../event-viewer/.output/public', // Adjust this path to your main entry file
  output: {
    file: 'bundle.js',
    format: 'iife',
    name: 'NuxtApp'
  },
  plugins: [
    resolve(),
    commonjs(),
    terser()
  ]
};
