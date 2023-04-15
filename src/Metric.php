<?php

namespace Darcher\PineconePhp;

enum Metric: string {
    case EUCLIDEAN = 'euclidian';
    case COSINE = 'cosine';
    case DOTPRODUCT = 'dotproduct';
}